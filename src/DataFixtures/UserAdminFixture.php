<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAdminFixture extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $users = new User();
        $users->setName('Theo meunier');
        $users->setEmail('theo.meunier41@gmail.com');
        $users->setIsVerified(true);
        $users->setPassword($this->passwordHasher->hashPassword(
            $users,
            'theotheo',
        ));

        $manager->persist($users);
    }
}
