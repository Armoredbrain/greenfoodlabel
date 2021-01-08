<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{
    const TYPES = [
        'Restaurateur',
        'Boulanger',
    ];

    public function load(ObjectManager $manager) : void
    {
        foreach (self::TYPES as $typeName) {
            $type = new Type();
            $type->setName($typeName);
            $this->addReference($typeName, $type);
            $manager->persist($type);
        }
        $manager->flush();
    }
}
