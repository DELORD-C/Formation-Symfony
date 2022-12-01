<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter {
    const EDIT = 'edit';
    const ADMIN = 'admin';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::ADMIN, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof User) {
            return false;
        }

        $user = $subject;

        return match($attribute) {
            self::ADMIN => (in_array('ROLE_ADMIN', $currentUser->getRoles())),
            self::EDIT => $this->canEdit($currentUser, $user)
        };
    }

    private function canEdit(User $currentUser, User $user): bool
    {
       return (
           in_array('ROLE_ADMIN', $currentUser->getRoles()) ||
           $user === $currentUser);
    }
}