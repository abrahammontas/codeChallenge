<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\User;
use App\Entity\Address;
use App\Entity\UserType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $client = new UserType();
        $client->setType('Client');
        $manager->persist($client);

        $driver = new UserType();
        $driver->setType('Driver');
        $manager->persist($driver);


        // create 5 clients!
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setName('name'.$i);
            $user->setLastname('lastname'.$i);
            $user->setEmail('email'.$i.'@email.com');
            $user->setPhone('3462263168 '.$i);
            $user->setType($client);
            $manager->persist($user);
        }

        // create 5 drivers!
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setName('name'.$i);
            $user->setLastname('lastname'.$i);
            $user->setEmail('email'.$i.'@email.com');
            $user->setPhone('3462262168 '.$i);
            $user->setType($driver);
            $manager->persist($user);
        }

        $country = new Country();
        $country->setName('Spain');
        $country->setSlug('spain');
        $manager->persist($country);

        $city = new City();
        $city->setName('Madrid');
        $city->setCountry($country);
        $manager->persist($city);

        // create 5 address!
        for ($i = 0; $i < 5; $i++) {
            $address = new Address();
            $address->setVia('Pilar de zaragoza '.$i);
            $address->setNumber($i);
            $address->setDoor('A');
            $address->setFloor($i);
            $address->setCity($city);
            $address->setPostalCode('28028');
            $manager->persist($address);
        }

        $manager->flush();
    }
}