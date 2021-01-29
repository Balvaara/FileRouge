<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        
        return in_array($attribute, ['POST', 'DELET'])
            && $subject instanceof User;
    }

    protected function voteOnAttribute($attribute, $use, TokenInterface $token)
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($user->getRoles()==['ROLE_SUP_ADMIN'] || $user->getRoles()==['ROLE_ADMIN']
                 || $user->getRoles()==['ROLE_PARTENAIRE'] ) {
            return true;
        }
        if ($user->getRoles()=='ROLE_CAISSIER') {
            return false;
        }
        switch ($attribute) {
            case 'POST':
               return [$user->getRoles()==['ROLE_SUP_ADMIN']
               , $user->getRoles()==['ROLE_ADMIN'] ,
                $user->getRoles()==['ROLE_PARTENAIRE']];
                break;
        }

        return false;
    }
}
