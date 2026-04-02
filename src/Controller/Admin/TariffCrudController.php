<?php

namespace App\Controller\Admin;

use App\Entity\Tariff;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

#[IsGranted('ROLE_ADMIN')]
class TariffCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tariff::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Tariff')
            ->setEntityLabelInPlural('Tariffs');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield ChoiceField::new('dayRange')->setChoices([
            'Mon-Thu' => 'mon_thu',
            'Fri-Sun' => 'fri_sun',
        ]);
        yield TimeField::new('startTime');
        yield TimeField::new('endTime');
        yield MoneyField::new('pricePerHour')->setCurrency('EUR')->setStoredAsCents(false);
    }
}
