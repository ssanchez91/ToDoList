<?php

namespace App\Security\Voter;

use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    /**
     * supprort function
     *
     * @param  $attribute
     * @param [type] $subject
     * @return Boolean
     */
    protected function supports($attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['DELETE'])
            && $subject instanceof \App\Entity\Task;
    }

    /**
     * check if user is authorize to use this resource
     *
     * @param [type] $attribute
     * @param [type] $subject
     * @param TokenInterface $token
     * @return Boolean
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        switch ($attribute) {
            case 'DELETE':
                if (!$subject->getAuthor()) {
                    return in_array('ROLE_ADMIN', $user->getRoles());
                }

                return $subject->getAuthor()->getId() == $user->getId();

                break;
                // @codeCoverageIgnoreStart
        }
    }
    // @codeCoverageIgnoreEnd
}
