<?php

namespace App\Controller\Admin;

use App\Entity\Lane;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class LaneCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lane::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Lane')
            ->setEntityLabelInPlural('Lanes')
            ->setDefaultSort(['number' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('number');
        yield BooleanField::new('hasBumpers');
    }
}
