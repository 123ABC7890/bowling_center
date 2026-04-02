<?php

namespace App\Controller;

use App\Entity\DayRange;
use App\Entity\Lane;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\LaneRepository;
use App\Repository\ReservationRepository;
use App\Repository\TariffRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/new', name: 'app_reservation_new')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        TariffRepository $tariffRepository,
        ReservationRepository $reservationRepository,
        LaneRepository $laneRepository,
        MailerInterface $mailer,
    ): Response {
        // Read GET params for availability preview
        $qDate = $request->query->get('date');
        $qStartHour = $request->query->get('startHour');
        $qDuration = $request->query->getInt('duration', 1);
        $qAdults = $request->query->getInt('adults', 1);
        $qChildren = $request->query->getInt('children', 0);
        $qLane = $request->query->getInt('lane', 0);
        $qSnack = $request->query->getInt('snack', 0);
        $qParty = $request->query->getInt('party', 0);
        $qName = $request->query->get('name', '');
        $qEmail = $request->query->get('email', '');
        $qPhone = $request->query->get('phone', '');

        $selectedDate = $qDate ? new \DateTime($qDate) : new \DateTime();

        $reservation = new Reservation();
        $reservation->numberOfAdults = $qAdults;
        $reservation->numberOfChildren = $qChildren;
        $reservation->name = $qName ?: null;
        $reservation->email = $qEmail ?: null;
        $reservation->phone = $qPhone ?: null;

        if ($qLane) {
            $reservation->lane = $laneRepository->find($qLane);
        }
        if ($qSnack) {
            $reservation->snackPackage = $em->find(\App\Entity\Package::class, $qSnack);
        }
        if ($qParty) {
            $reservation->partyPackage = $em->find(\App\Entity\Package::class, $qParty);
        }

        $isGuest = !$this->getUser();
        $form = $this->createForm(ReservationType::class, $reservation, [
            'is_guest' => $isGuest,
            'selected_date' => $qDate,
            'selected_start_hour' => $qStartHour,
            'selected_duration' => $qDuration,
            'selected_lane' => $qLane,
            'selected_snack' => $qSnack,
            'selected_party' => $qParty,
        ]);
        $form->handleRequest($request);

        // Build availability grid
        $dayOfWeek = (int) $selectedDate->format('N');
        $isWeekend = $dayOfWeek >= 6;
        $isFriday = $dayOfWeek === 5;
        $openHour = 14;
        $closeHour = $isWeekend ? 24 : 22;
        if ($isFriday) {
            $closeHour = 22;
        }

        $dayStart = (clone $selectedDate)->setTime($openHour, 0);
        $dayEnd = $isWeekend
            ? (clone $selectedDate)->modify('+1 day')->setTime(0, 0)
            : (clone $selectedDate)->setTime($closeHour, 0);

        $lanes = $laneRepository->findBy([], ['number' => 'ASC']);
        $dayReservations = $reservationRepository->findByDateRange($dayStart, $dayEnd);
        $hours = range($openHour, $closeHour - 1);

        $grid = [];
        foreach ($lanes as $lane) {
            $grid[$lane->getId()] = [];
            foreach ($hours as $hour) {
                $grid[$lane->getId()][$hour] = null;
            }
        }
        foreach ($dayReservations as $r) {
            $laneId = $r->lane->getId();
            $resStart = (int) $r->startTime->format('H');
            $resEnd = (int) $r->endTime->format('H');
            if ($resEnd === 0) {
                $resEnd = 24;
            }
            foreach ($hours as $hour) {
                if ($hour >= $resStart && $hour < $resEnd) {
                    $grid[$laneId][$hour] = $r;
                }
            }
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->get('date')->getData();
            $startHour = $form->get('startHour')->getData();
            $duration = (int) $form->get('duration')->getData();

            $startTime = new \DateTime($date->format('Y-m-d') . ' ' . $startHour->format('H:i'));
            $endTime = (clone $startTime)->modify("+{$duration} hours");

            if ($reservation->numberOfChildren > 0
                && ($reservation->numberOfAdults > Lane::MAX_ADULTS_WITH_CHILDREN
                    || $reservation->numberOfChildren > Lane::MAX_CHILDREN_WITH_ADULTS)) {
                $this->addFlash('danger', 'With children, maximum is ' . Lane::MAX_ADULTS_WITH_CHILDREN . ' adults and ' . Lane::MAX_CHILDREN_WITH_ADULTS . ' children.');
                return $this->render('reservation/new.html.twig', [
                    'form' => $form, 'lanes' => $lanes, 'hours' => $hours, 'grid' => $grid, 'selectedDate' => $selectedDate,
                ]);
            }

            $startDow = (int) $startTime->format('N');
            $dayRange = $startDow <= 4 ? DayRange::MonThu : DayRange::FriSun;

            $tariff = $tariffRepository->createQueryBuilder('t')
                ->where('t.dayRange = :dayRange')
                ->andWhere('t.startTime <= :time')
                ->andWhere('t.endTime > :time')
                ->setParameter('dayRange', $dayRange)
                ->setParameter('time', $startTime->format('H:i:s'))
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            if (!$tariff) {
                $this->addFlash('danger', 'No tariff available for the selected date and time. Please check our opening hours.');
                return $this->render('reservation/new.html.twig', [
                    'form' => $form, 'lanes' => $lanes, 'hours' => $hours, 'grid' => $grid, 'selectedDate' => $selectedDate,
                ]);
            }

            $conflict = $reservationRepository->createQueryBuilder('r')
                ->where('r.lane = :lane')
                ->andWhere('r.status != :cancelled')
                ->andWhere('r.startTime < :endTime')
                ->andWhere('r.endTime > :startTime')
                ->setParameter('lane', $reservation->lane)
                ->setParameter('cancelled', Reservation::STATUS_CANCELLED)
                ->setParameter('startTime', $startTime)
                ->setParameter('endTime', $endTime)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            if ($conflict) {
                $this->addFlash('danger', 'This lane is not available at the selected time. Please choose a different lane or time.');
                return $this->render('reservation/new.html.twig', [
                    'form' => $form, 'lanes' => $lanes, 'hours' => $hours, 'grid' => $grid, 'selectedDate' => $selectedDate,
                ]);
            }

            $rate = (float) $tariff->pricePerHour;
            $totalPrice = $rate * $duration;

            if ($reservation->snackPackage) {
                $totalPrice += (float) $reservation->snackPackage->price;
            }
            if ($reservation->partyPackage) {
                $totalPrice += (float) $reservation->partyPackage->price;
            }

            if ($this->getUser()) {
                $reservation->user = $this->getUser();
            }

            $reservation->tariff = $tariff;
            $reservation->appliedRate = (string) $rate;
            $reservation->startTime = $startTime;
            $reservation->endTime = $endTime;
            $reservation->totalPrice = (string) $totalPrice;

            $em->persist($reservation);
            $em->flush();

            $recipientEmail = $this->getUser()?->getUserIdentifier() ?? $reservation->email;
            if ($recipientEmail) {
                try {
                    $emailMsg = (new Email())
                        ->from('noreply@bowlingcenter-brooklyn.nl')
                        ->to($recipientEmail)
                        ->subject('Reservation confirmation - Bowlingcenter Brooklyn')
                        ->html($this->renderView('email/reservation_confirmation.html.twig', [
                            'reservation' => $reservation,
                        ]));

                    $mailer->send($emailMsg);
                } catch (\Exception $e) {
                }
            }

            $this->addFlash('success', 'Your reservation has been placed!');
            return $this->redirectToRoute('app_reservation_confirmation', ['id' => $reservation->getId()]);
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form,
            'lanes' => $lanes,
            'hours' => $hours,
            'grid' => $grid,
            'selectedDate' => $selectedDate,
        ]);
    }

    #[Route('/{id}/confirmation', name: 'app_reservation_confirmation')]
    public function confirmation(Reservation $reservation): Response
    {
        if ($this->getUser() && $reservation->user && $reservation->user !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('reservation/confirmation.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/my', name: 'app_my_reservations')]
    #[IsGranted('ROLE_USER')]
    public function list(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findByUser($this->getUser());
        $now = new \DateTime();

        return $this->render('reservation/list.html.twig', [
            'reservations' => $reservations,
            'now' => $now,
        ]);
    }

    #[Route('/{id}/cancel', name: 'app_reservation_cancel', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function cancel(Reservation $reservation, EntityManagerInterface $em, Request $request): Response
    {
        if ($reservation->user !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if (!$this->isCsrfTokenValid('cancel-' . $reservation->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        if ($reservation->status === Reservation::STATUS_CANCELLED) {
            $this->addFlash('danger', 'This reservation is already cancelled.');
            return $this->redirectToRoute('app_my_reservations');
        }

        if ($reservation->startTime < new \DateTime()) {
            $this->addFlash('danger', 'Cannot cancel a past reservation.');
            return $this->redirectToRoute('app_my_reservations');
        }

        $reservation->status = Reservation::STATUS_CANCELLED;
        $em->flush();

        $this->addFlash('success', 'Your reservation has been cancelled.');
        return $this->redirectToRoute('app_my_reservations');
    }
}
