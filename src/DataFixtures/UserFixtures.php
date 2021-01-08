<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager) : void
    {
        $user = new User();
        $user->setFirstname('Michel');
        $user->setLastname('Polnareff');
        $user->setEmail('polnareff@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'coucou'));
        $user->setActive(rand(0, 1));

        $manager->persist($user);

        $this->addReference('user', $user);

        $admin = new User();
        $admin->setFirstname('Super');
        $admin->setLastname('Admin');
        $admin->setEmail('wcs.green.food@gmail.com');
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, 'coucouadmin'));
        $admin->setActive(1);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $this->addReference('admin', $admin);

        $manager->flush();
    }
}
