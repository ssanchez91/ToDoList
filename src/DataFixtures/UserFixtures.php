<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public const USER_REFERENCE = 'user_';

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for($i=1; $i < 6 ; $i++)
        {
            $user = new User();
            $user->setUsername(self::USER_REFERENCE.$i);
            $user->setEmail(self::USER_REFERENCE.$i.'@yopmail.com');
            $user->setPassword($this->passwordEncoder->encodePassword($user,self::USER_REFERENCE.$i));

            $manager->persist($user);

            $this->addReference(self::USER_REFERENCE.$i, $user);
        }

        $manager->flush();
    }
}
