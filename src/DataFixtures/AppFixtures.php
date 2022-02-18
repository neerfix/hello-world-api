<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //FIXME use UserService to Create a new user

        $neerfix = (new User())
            ->setDateOfBirth(new DateTime('1996-12-26'))
            ->setEmail('nicolas@helloworld.ovh')
            ->setFirstname('nicolas')
            ->setLastname('notararigo')
            ->setUsername('neerfix')
            ->setIsVerify(true)
            ->setPassword('1234567890')
            ->setRoles(['ROLE_ADMIN'])
            ->setUuid('UUID-NEERFIX');

        $manager->persist($neerfix);
        $manager->flush();
    }
}
