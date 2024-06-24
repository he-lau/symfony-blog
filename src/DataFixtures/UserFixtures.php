<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('Jean');
        $user->setPassword('$2y$13$DmZq5H355N9AjzwFFuWA0uURLfKTrGLX6gQnAFEgwlk01lMOSUd2C');

        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword('$2y$13$2IeNZmeh7dGtknXhm13/3OW7L65ovtQcPj1UpmPMav4r5pErUsq92');
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }
}
