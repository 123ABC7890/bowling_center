<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Reservation')
            ->setEntityLabelInPlural('Reservations')
            ->setDefaultSort(['startTime' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::BATCH_DELETE, 'ROLE_ADMIN');
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('user')->setRequired(false);
        yield TextField::new('name')->hideOnIndex();
        yield EmailField::new('email')->hideOnIndex();
        yield TelephoneField::new('phone')->hideOnIndex();
        yield AssociationField::new('lane');
        yield AssociationField::new('tariff');
        yield MoneyField::new('appliedRate')->setCurrency('EUR')->setStoredAsCents(false);
        yield DateTimeField::new('startTime');
        yield DateTimeField::new('endTime');
        yield IntegerField::new('numberOfAdults', 'Adults');
        yield IntegerField::new('numberOfChildren', 'Children');
        yield AssociationField::new('snackPackage');
        yield AssociationField::new('partyPackage');
        yield MoneyField::new('totalPrice')->setCurrency('EUR')->setStoredAsCents(false);
        yield ChoiceField::new('status')->setChoices([
            'Pending' => 'pending',
            'Confirmed' => 'confirmed',
            'Cancelled' => 'cancelled',
        ]);
        yield DateTimeField::new('createdAt')->hideOnForm();
        yield DateTimeField::new('updatedAt')->hideOnForm();
    }
}
