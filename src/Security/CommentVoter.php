<?php

namespace App\Security;

use App\Entity\Post\Comment as PostComment;
use App\Entity\Review\Comment as ReviewComment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    const SITUATIONS = ['DELETE', 'UPDATE'];

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::SITUATIONS)) {
            return false;
        }

        if (!$subject instanceof PostComment && !$subject instanceof ReviewComment) {
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
            'DELETE' => $this->canDelete($subject, $user),
            'UPDATE' => $this->canUpdate($subject, $user)
        };
    }

    private function canDelete(mixed $subject, User $user): bool
    {
        if ($subject->getUser() === $user) {
            return true;
        }
        else if ($subject instanceof PostComment) {
            return $user === $subject->getPost()->getUser();
        }
        else {
            return $user === $subject->getReview()->getUser();
        }
    }

    private function canUpdate(mixed $subject, User $user): bool
    {
    return $subject->getUser() === $user;
    }
}