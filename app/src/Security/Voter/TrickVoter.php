<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TrickVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['CAN_EDIT', 'CAN_DELETE'])
            && $subject instanceof \App\Entity\Trick;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        if(in_array('ROLE_ADMIN', $user->getRoles()))
        {
            return true;
        }

        switch ($attribute) {
            case 'CAN_EDIT':
                // logic to determine if the user can EDIT
                if($subject->getAuthor() === $user){
                    return true;
                }
                return false;
                break;
            case 'CAN_DELETE':
                if($subject->getAuthor() === $user){
                    return true;
                }
                return false;
                break;
            default:
                return false;
        }

        return false;
    }
}
