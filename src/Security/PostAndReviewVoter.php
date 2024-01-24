<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\Review;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostAndReviewVoter extends Voter
{
    const SITUATIONS = ['DELETE', 'UPDATE'];

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::SITUATIONS)) {
            return false;
        }

        if (!$subject instanceof Post && !$subject instanceof Review) {
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

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        return match($attribute) {
            'DELETE', 'UPDATE' => $this->canUpdateOrDelete($subject, $user)
        };
    }

    private function canUpdateOrDelete(mixed $subject, User $user): bool
    {
        return $subject->getUser() === $user;
    }
}