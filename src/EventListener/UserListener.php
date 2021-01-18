<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    /**
     * Represente encoder password interface
     *
     * @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    /**
     * __construct function
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Encode password before persist a usern
     *
     * @param LifecycleEventArgs $args
     * @return void
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof User) {
            if (strlen($entity->getPassword()) > 0) {
                $entity->setPassword($this->encoder->encodePassword($entity, $entity->getPassword()));
            }
        }
    }

    /**
     * call prePersist function when a user is updated
     *
     * @param LifecycleEventArgs $args
     * 
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        return $this->prePersist($args);
    }
}
