<?php

namespace App\Controller;

use App\Entity\DayRange;
use App\Entity\Lane;
use App\Entity\Reservation;
use App\Form\ReservationType;
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

class ReservationController extends AbstractController
{
    #[Route('/my-reservations', name: 'app_my_reservations')]
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

    #[Route('/reservation/{id}/cancel', name: 'app_reservation_cancel', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function cancel(Reservation $reservation, EntityManagerInterface $em, Request $request): Response
    {
        if ($reservation->user !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if (!$this->isCsrfTokenValid('cancel-' . $reservation->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        if ($reservation->status === 'cancelled') {
            $this->addFlash('danger', 'This reservation is already cancelled.');
            return $this->redirectToRoute('app_my_reservations');
        }

        if ($reservation->startTime < new \DateTime()) {
            $this->addFlash('danger', 'Cannot cancel a past reservation.');
            return $this->redirectToRoute('app_my_reservations');
        }

        $reservation->status = 'cancelled';
        $em->flush();

        $this->addFlash('success', 'Your reservation has been cancelled.');
        return $this->redirectToRoute('app_my_reservations');
    }


    #[Route('/reservation/new', name: 'app_reservation_new')]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        TariffRepository $tariffRepository,
        ReservationRepository $reservationRepository,
        MailerInterface $mailer,
    ): Response {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->get('date')->getData();
            $startHour = $form->get('startHour')->getData();
            $duration = (int) $form->get('duration')->getData();

            $startTime = new \DateTime($date->format('Y-m-d') . ' ' . $startHour->format('H:i'));
            $endTime = (clone $startTime)->modify("+{$duration} hours");

            // Validate capacity
            if ($reservation->numberOfChildren > 0
                && ($reservation->numberOfAdults > Lane::MAX_ADULTS_WITH_CHILDREN
                    || $reservation->numberOfChildren > Lane::MAX_CHILDREN_WITH_ADULTS)) {
                $this->addFlash('danger', 'With children, maximum is ' . Lane::MAX_ADULTS_WITH_CHILDREN . ' adults and ' . Lane::MAX_CHILDREN_WITH_ADULTS . ' children.');
                return $this->render('reservation/new.html.twig', ['form' => $form]);
            }

            // Determine day range
            $dayOfWeek = (int) $startTime->format('N'); // 1=Mon, 7=Sun
            $dayRange = $dayOfWeek <= 4 ? DayRange::MonThu : DayRange::FriSun;

            // Find matching tariff
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
                return $this->render('reservation/new.html.twig', ['form' => $form]);
            }

            // Check lane availability
            $conflict = $reservationRepository->createQueryBuilder('r')
                ->where('r.lane = :lane')
                ->andWhere('r.status != :cancelled')
                ->andWhere('r.startTime < :endTime')
                ->andWhere('r.endTime > :startTime')
                ->setParameter('lane', $reservation->lane)
                ->setParameter('cancelled', 'cancelled')
                ->setParameter('startTime', $startTime)
                ->setParameter('endTime', $endTime)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            if ($conflict) {
                $this->addFlash('danger', 'This lane is not available at the selected time. Please choose a different lane or time.');
                return $this->render('reservation/new.html.twig', ['form' => $form]);
            }

            // Calculate total price
            $rate = (float) $tariff->pricePerHour;
            $totalPrice = $rate * $duration;

            if ($reservation->snackPackage) {
                $totalPrice += (float) $reservation->snackPackage->price;
            }
            if ($reservation->partyPackage) {
                $totalPrice += (float) $reservation->partyPackage->price;
            }

            $reservation->user = $this->getUser();
            $reservation->tariff = $tariff;
            $reservation->appliedRate = (string) $rate;
            $reservation->startTime = $startTime;
            $reservation->endTime = $endTime;
            $reservation->totalPrice = (string) $totalPrice;

            $em->persist($reservation);
            $em->flush();

            // Send confirmation email
            try {
                $email = (new Email())
                    ->from('noreply@bowlingcenter-brooklyn.nl')
                    ->to($this->getUser()->getUserIdentifier())
                    ->subject('Reservation confirmation — Bowlingcenter Brooklyn')
                    ->html($this->renderView('email/reservation_confirmation.html.twig', [
                        'reservation' => $reservation,
                    ]));

                $mailer->send($email);
            } catch (\Exception $e) {
                // Email sending should not block reservation
            }

            $this->addFlash('success', 'Your reservation has been placed!');
            return $this->redirectToRoute('app_reservation_confirmation', ['id' => $reservation->getId()]);
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/reservation/{id}/confirmation', name: 'app_reservation_confirmation')]
    #[IsGranted('ROLE_USER')]
    public function confirmation(Reservation $reservation): Response
    {
        if ($reservation->user !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('reservation/confirmation.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
