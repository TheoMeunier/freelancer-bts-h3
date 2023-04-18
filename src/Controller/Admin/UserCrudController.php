<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des utilisateurs')
            ->setPageTitle('new', 'Créer un utilisateur')
            ->setPageTitle('edit', 'Modifier mon utilisateur')
            ->setDateTimeFormat('d/m/Y')
            ->setPaginatorPageSize(15);
    }

    public function persistEntity(
        EntityManagerInterface|string $em,
        $entityInstance
    ): void {
        if (!$entityInstance instanceof User) {
            return;
        }

        $entityInstance->setPassword(
            $this->passwordEncoder->hashPassword($entityInstance, $entityInstance->getPassword()),
        );

        parent::persistEntity($em, $entityInstance);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->setColumns(6)
                ->onlyOnIndex(),

            TextField::new('name')
                ->setColumns(6),

            EmailField::new('email')
                ->setColumns(6),

            ChoiceField::new('roles')
                ->setChoices([
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                ])
                ->setFormTypeOption('attr', ['placeholder' => 'Choisir une role'])
                ->setColumns(12)
                ->allowMultipleChoices(),

            TextField::new('password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOption('type', PasswordType::class)
                ->setFormTypeOption('first_options', ['label' => 'Password'])
                ->setFormTypeOption('second_options', ['label' => 'Repeat Password'])
                ->setColumns(6)
                ->onlyWhenCreating(),

            BooleanField::new('isVerified')
                ->renderAsSwitch(true)
                ->onlyOnIndex(),

            DateTimeField::new('created_at')
                ->onlyOnIndex(),
        ];
    }
}
