<?php

namespace App\Controller\Admin;

use App\Controller\Admin\LaneCrudController;
use App\Controller\Admin\PackageCrudController;
use App\Controller\Admin\ReservationCrudController;
use App\Controller\Admin\TariffCrudController;
use App\Controller\Admin\UserCrudController;
use App\Entity\Reservation;
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
            $data['confirmedCount'] = $this->reservationRepository->countByStatus(Reservation::STATUS_CONFIRMED);
            $data['pendingCount'] = $this->reservationRepository->countByStatus(Reservation::STATUS_PENDING);
            $data['cancelledCount'] = $this->reservationRepository->countByStatus(Reservation::STATUS_CANCELLED);
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
        yield MenuItem::linkTo(ReservationCrudController::class, 'Reservations', 'fa fa-calendar-check');
        yield MenuItem::linkTo(LaneCrudController::class, 'Lanes', 'fa fa-bowling-ball')
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkTo(TariffCrudController::class, 'Tariffs', 'fa fa-euro-sign')
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkTo(PackageCrudController::class, 'Packages', 'fa fa-gift')
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkTo(UserCrudController::class, 'Users', 'fa fa-users')
            ->setPermission('ROLE_ADMIN');
    }
}
