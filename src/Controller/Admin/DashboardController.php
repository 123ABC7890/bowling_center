<?php

namespace App\Controller\Admin;

use App\Entity\Lane;
use App\Entity\Package;
use App\Entity\Reservation;
use App\Entity\Tariff;
use App\Entity\User;
use App\Repository\LaneRepository;
use App\Repository\ReservationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private ReservationRepository $reservationRepository,
        private LaneRepository $laneRepository,
    ) {
    }

    public function index(): Response
    {
        $todayReservations = $this->reservationRepository->findTodayReservations();
        $activeNow = $this->reservationRepository->findActiveNow();
        $allLanes = $this->laneRepository->findBy([], ['number' => 'ASC']);

        $occupiedLaneIds = [];
        foreach ($activeNow as $reservation) {
            $occupiedLaneIds[$reservation->lane->getId()] = $reservation;
        }

        $data = [
            'todayReservations' => $todayReservations,
            'allLanes' => $allLanes,
            'occupiedLaneIds' => $occupiedLaneIds,
            'occupancyNow' => count($activeNow),
            'totalLanes' => count($allLanes),
            'isAdmin' => $this->isGranted('ROLE_ADMIN'),
        ];

        if ($this->isGranted('ROLE_ADMIN')) {
            $data['totalReservations'] = $this->reservationRepository->countTotal();
            $data['confirmedCount'] = $this->reservationRepository->countByStatus('confirmed');
            $data['pendingCount'] = $this->reservationRepository->countByStatus('pending');
            $data['cancelledCount'] = $this->reservationRepository->countByStatus('cancelled');
            $data['totalRevenue'] = $this->reservationRepository->totalRevenue();
        }

        return $this->render('admin/dashboard.html.twig', $data);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bowlingcenter Brooklyn');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Reservations', 'fa fa-calendar-check', Reservation::class);
        yield MenuItem::linkToCrud('Lanes', 'fa fa-bowling-ball', Lane::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Tariffs', 'fa fa-euro-sign', Tariff::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Packages', 'fa fa-gift', Package::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class)
            ->setPermission('ROLE_ADMIN');
    }
}
