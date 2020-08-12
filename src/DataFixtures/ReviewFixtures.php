<?php

namespace App\DataFixtures;
use App\Entity\Hotel;
use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;

class ReviewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $generator = \Faker\Factory::create();

        for ($i = 0; $i < 100000; $i++){
            $review = new Review();
            $review->setHotel($this->getReference($generator->numberBetween(1, 10)));
            $review->setScore($generator->numberBetween(1, 100));
            $review->setComment($generator->realText());
            $review->setCreatedDate($generator->dateTimeBetween('-2 years', 'now'));
            $manager->persist($review);
        }
        $manager->flush();

    }

    public function getDependencies()
    {
        return array(
            HotelFixtures::class,
        );
    }
}
