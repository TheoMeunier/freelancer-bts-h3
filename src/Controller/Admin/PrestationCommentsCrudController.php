<?php

namespace App\Controller\Admin;

use App\Entity\PrestationComments;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class PrestationCommentsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PrestationComments::class;
    }

    public function configureCrud(Crud $curd): Crud
    {
        return $curd
            ->setPageTitle('index', 'Liste des commentaires')
            ->setDateTimeFormat('d/m/Y')
            ->setPaginatorPageSize(15);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),

            TextareaField::new('content')
                ->onlyOnIndex(),

            AssociationField::new('prestation')
                ->onlyOnIndex(),

            AssociationField::new('user')
                ->onlyOnIndex(),

            DateTimeField::new('updated_at')
                ->onlyOnIndex(),

            DateTimeField::new('created_at')
                ->onlyOnIndex(),
        ];
    }
}
