<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter {
    const CASES = ['CREATE', 'EDIT', 'SHOW', 'DELETE', 'GRANT'];

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::CASES)) {
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

        $subject;

        return match($attribute) {
            'CREATE', 'SHOW' => true,
            'EDIT', 'DELETE' => $this->canEdit($subject, $user),
            'GRANT' => $this->canGrant($subject, $user)
        };
    }

    private function canEdit(User $subject, User $user): bool
    {
        return (
            in_array('ROLE_ADMIN', $user->getRoles()) ||
            $user === $subject
        );
    }

    private function canGrant(User $subject, User $user): bool
    {
        return (
            in_array('ROLE_ADMIN', $user->getRoles()) &&
            $user !== $subject
        );
    }
}