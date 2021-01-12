<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['DELETE'])
            && $subject instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        switch ($attribute) {
            case 'DELETE':
                if (!$subject->getAuthor()) {
                    return in_array('ROLE_ADMIN', $user->getRoles());
                }

                return $subject->getAuthor()->getId() == $user->getId();

                // return true or false
                break;
        }
    }
}
