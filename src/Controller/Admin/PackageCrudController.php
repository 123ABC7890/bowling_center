<?php

namespace App\Controller\Admin;

use App\Entity\Package;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

#[IsGranted('ROLE_ADMIN')]
class PackageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Package::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Package')
            ->setEntityLabelInPlural('Packages');
    }

    public function configureFields(string $pageName): iterable
    {
        yield ChoiceField::new('type')->setChoices([
            'Snack' => Package::TYPE_SNACK,
            'Party' => Package::TYPE_PARTY,
        ]);
        yield TextField::new('name');
        yield TextareaField::new('description')->hideOnIndex();
        yield MoneyField::new('price')->setCurrency('EUR')->setStoredAsCents(false);
    }
}
