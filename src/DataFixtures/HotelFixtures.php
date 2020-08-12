<?php

namespace App\DataFixtures;

use App\Entity\Hotel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\ORM\Propel\Populator;

class HotelFixtures extends Fixture
{
    public static $id;
    public function load(ObjectManager $manager)
    {
        $generator = \Faker\Factory::create();
//        $populator = new \Faker\ORM\Propel\Populator($generator);
//            $populator->addEntity(Hotel::class,10, array(
//            'name' => function() use ($generator) { return $generator->unique()->city; }
//        ));
//        $insertedPKs = $populator->execute();
         for ($i = 1; $i <= 10; $i++){
             $hotel = new Hotel();
             $hotel->setName($generator->unique()->city);
             $manager->persist($hotel);
             $this->addReference($i, $hotel);
    }
        $manager->flush();
//         self::$id = $generator->numberBetween(1, 10);
//
    }
}
