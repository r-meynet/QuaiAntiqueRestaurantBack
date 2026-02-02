<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public const USER_NB_TUPLES = 20;
    
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        
        for ($i = 1; $i <= self::USER_NB_TUPLES; $i++) {
            $user = (new User())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setGuestNumber(random_int(0, 10))
                ->setEmail($faker->email())
                ->setCreatedAt(new DateTimeImmutable());

            $user->setPassword($this->passwordHasher->hashPassword($user, 'password' . $i));

            $manager->persist($user);
        }


        $manager->flush();
    }

    // Exemple d'implémentation des groupes pour load uniquement certaines fixtures
    public static function getGroups(): array
    {
        // Cette fixture est indépendante des autres
        return ['independent'];
    }
}
