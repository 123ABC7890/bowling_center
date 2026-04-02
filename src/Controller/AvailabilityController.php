<?php

namespace App\Controller;

use App\Repository\LaneRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AvailabilityController extends AbstractController
{
    #[Route('/availability', name: 'app_availability')]
    public function calendar(
        Request $request,
        LaneRepository $laneRepository,
        ReservationRepository $reservationRepository,
    ): Response {
        $dateStr = $request->query->get('date', (new \DateTime())->format('Y-m-d'));
        $date = new \DateTime($dateStr);

        $dayOfWeek = (int) $date->format('N');
        $isWeekend = $dayOfWeek >= 6;

        $openHour = 14;
        $closeHour = $isWeekend ? 24 : 22;

        $dayStart = (clone $date)->setTime($openHour, 0);
        $dayEnd = (clone $date)->setTime(0, 0)->modify('+1 day');
        if ($isWeekend) {
            $dayEnd = (clone $date)->modify('+1 day')->setTime(0, 0);
        }

        $isFriday = $dayOfWeek === 5;
        if ($isFriday) {
            $closeHour = 22;
            $dayEnd = (clone $date)->setTime($closeHour, 0);
        }

        $lanes = $laneRepository->findBy([], ['number' => 'ASC']);
        $reservations = $reservationRepository->findByDateRange($dayStart, $dayEnd);

        $hours = range($openHour, $closeHour - 1);

        // Build occupancy grid: lane_id => hour => reservation
        $grid = [];
        foreach ($lanes as $lane) {
            $grid[$lane->getId()] = [];
            foreach ($hours as $hour) {
                $grid[$lane->getId()][$hour] = null;
            }
        }

        foreach ($reservations as $reservation) {
            $laneId = $reservation->lane->getId();
            $resStart = (int) $reservation->startTime->format('H');
            $resEnd = (int) $reservation->endTime->format('H');
            if ($resEnd === 0) {
                $resEnd = 24;
            }

            foreach ($hours as $hour) {
                if ($hour >= $resStart && $hour < $resEnd) {
                    $grid[$laneId][$hour] = $reservation;
                }
            }
        }

        $prevDate = (clone $date)->modify('-1 day')->format('Y-m-d');
        $nextDate = (clone $date)->modify('+1 day')->format('Y-m-d');

        return $this->render('availability/calendar.html.twig', [
            'date' => $date,
            'lanes' => $lanes,
            'hours' => $hours,
            'grid' => $grid,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
        ]);
    }
}
