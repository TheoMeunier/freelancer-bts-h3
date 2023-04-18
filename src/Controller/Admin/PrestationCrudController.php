<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Prestation;
use App\Services\FileServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Choice;

class PrestationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Prestation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des prestations')
            ->setPageTitle('new', 'Créer une prestation')
            ->setPageTitle('edit', 'Modifier ma prestation')
            ->setDateTimeFormat('d/m/Y')
            ->setPaginatorPageSize(9);
    }

    public function persistEntity(
        EntityManagerInterface|string $em,
        $entityInstance,
    ): void {
        if (!$entityInstance instanceof Prestation) {
            return;
        }

        parent::persistEntity($em, $entityInstance);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),

            TextField::new('title')
                ->setColumns(6),

            NumberField::new('price')
                ->setColumns(6),

            ImageField::new('image')
                ->setBasePath('/upload')
                ->setUploadDir('public/upload'),

            AssociationField::new('user')
                ->setColumns(6),

            AssociationField::new('categories')
                ->setColumns(6),

            TextareaField::new('description')
                ->setColumns(12),

            TextEditorField::new('content')
                ->setColumns(12)
                ->hideOnIndex(),

            DateTimeField::new('updated_at')
                ->onlyOnIndex(),

            DateTimeField::new('created_at')
                ->onlyOnIndex(),
        ];
    }
}
