<?php

namespace App\DataFixtures;

use App\Entity\Facility;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FacilityFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $facility = new Facility();
            $facility->setLegalEntity($faker->company);
            $facility->setLegalStatus($faker->companySuffix);
            $facility->setCapital($faker->numberBetween());
            $facility->setAdress($faker->streetAddress);
            $facility->setMatriculation($faker->numberBetween());
            $facility->setManagerFirstname($faker->firstName);
            $facility->setManagerLastname($faker->lastName);
            $facility->setRole($faker->jobTitle);
            $facility->setMatriculationCity($faker->city);
            $facility->setCity($faker->city);
            $facility->setZip($faker->numberBetween(10000, 99999));
            $facility->setIsValid(rand(0, 1));
            $facility->setCreatedAt($faker->dateTime);
            $facility->setCountry($faker->country);

            $manager->persist($facility);

            $facility->setType($this->getReference(TypeFixtures::TYPES[array_rand(TypeFixtures::TYPES)]));

            $status = (Facility::STATUS);
            $facility->setStatus($status[rand(0, 3)]);

            $facility->setUser($this->getReference('user'));
        }

        $manager->flush();
    }

    public function getDependencies() : array
    {
        return [TypeFixtures::class];
    }
}
