<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public const PICTURE_NB_TUPLES = 20;

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= self::PICTURE_NB_TUPLES; $i++) {
            $picture = (new Picture())
                ->setTitle('Image n°' . $i)
                ->setSlug('image-n-' . $i)
                ->setRestaurant($this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE . random_int(1, 20), Restaurant::class))
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($picture);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RestaurantFixtures::class];
    }
}
