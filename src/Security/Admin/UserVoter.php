<?php

namespace App\Security\Admin;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const SITUATIONS = ['ADMINUPDATE'];

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::SITUATIONS)) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            return true;
        }
        else if (!in_array('ROLE_SUPER_ADMIN', $subject->getRoles())) {
            return true;
        }

        return false;
    }
}