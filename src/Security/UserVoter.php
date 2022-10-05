<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match($attribute) {
            self::VIEW => $this->canView($user),
            self::EDIT => $this->canEdit($subject, $user),
            default => throw new \LogicException('This code may not be reached !')
        };
    }

    function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    function canView(User $user): bool
    {
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }
        return false;
    }

    function canEdit(User $subject, User $user): bool
    {
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }
        return $user === $subject;
    }
}