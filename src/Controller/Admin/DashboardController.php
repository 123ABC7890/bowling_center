<?php

namespace App\Controller\Admin;

use App\Entity\Lane;
use App\Entity\Package;
use App\Entity\Reservation;
use App\Entity\Tariff;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(ReservationCrudController::class)->generateUrl());
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
        yield MenuItem::linkToCrud('Lanes', 'fa fa-bowling-ball', Lane::class);
        yield MenuItem::linkToCrud('Tariffs', 'fa fa-euro-sign', Tariff::class);
        yield MenuItem::linkToCrud('Packages', 'fa fa-gift', Package::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-users', User::class);
    }
}
