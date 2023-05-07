<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\InformationUser;
use App\Entity\Prestation;
use App\Entity\PrestationComments;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($t = 0; $t < 15; $t++) {
            //creation des catégories
            $categories = new Category();
            $categories->setName($faker->name);

            $manager->persist($categories);
            $this->addReference('category_' . $t, $categories);
        }

        for ($i = 0; $i < 50; $i++) {
            //creation d'utilisateur
            $users = new User();
            $users->setName($faker->name);
            $users->setEmail($faker->email);
            $users->setIsVerified(true);
            $users->setPassword($this->passwordHasher->hashPassword(
                $users,
                $faker->password,
            ));

            $manager->persist($users);

            //creation d'information user
            $info = new InformationUser();
            $info->setPhone($faker->phoneNumber);
            $info->setCity($faker->city);
            $info->setPays($faker->country);
            $info->setDescription($faker->text());
            $info->setUser($users);

            $manager->persist($info);

            // creation de prestation
            for ($j = 0; $j < 5; $j++) {
                $prestation = new Prestation();
                $prestation->setTitle($faker->title);
                $prestation->setPrice(500);
                $prestation->setContent($faker->paragraph(10));
                $prestation->setDescription($faker->text());
                $prestation->setImage('https://picsum.photos/200/300');
                $prestation->setUser($users);
                $prestation->addCategory($this->getReference('category_' . $faker->numberBetween(0, 13)));

                $manager->persist($prestation);

                for ($c = 0; $c < 3; $c++) {
                    $comment = new PrestationComments();
                    $comment->setContent($faker->text);
                    $comment->setPrestation($prestation);
                    $comment->setUser($users);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
