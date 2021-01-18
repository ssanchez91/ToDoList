<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    /**
     * Constante represent user
     */
    public const USER_REFERENCE = 'user_';

    /**
     * Allow to create users fixtures
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 6; $i++) {
            $user = new User();
            $user->setUsername(self::USER_REFERENCE . $i);
            $user->setEmail(self::USER_REFERENCE . $i . '@yopmail.com');
            $user->setPassword(self::USER_REFERENCE . $i);
            if ($i == 5) {
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                $user->setRoles(['ROLE_USER']);
            }


            $manager->persist($user);

            $this->addReference(self::USER_REFERENCE . $i, $user);
        }

        $manager->flush();
    }
}
