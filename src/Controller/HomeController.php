<?php

namespace App\Controller;

use App\Entity\Package;
use App\Repository\PackageRepository;
use App\Repository\TariffRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TariffRepository $tariffRepository, PackageRepository $packageRepository): Response
    {
        return $this->render('index.html.twig', [
            'tariffs' => $tariffRepository->findAll(),
            'snackPackages' => $packageRepository->findBy(['type' => Package::TYPE_SNACK]),
            'partyPackages' => $packageRepository->findBy(['type' => Package::TYPE_PARTY]),
        ]);
    }
}
