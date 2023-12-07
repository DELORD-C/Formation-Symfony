<?php

namespace App\Security;
use App\Entity\Post\Comment;
use App\Entity\Review\Comment as ReviewComment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    const CREATE = 'createComment';
    const DELETE = 'delete';
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::CREATE, self::DELETE])) {
            return false;
        }

        if (
            $attribute == self::DELETE &&
            !$subject instanceof Comment &&
            !$subject instanceof ReviewComment
        ) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(
        string $attribute,
        mixed $subject,
        TokenInterface $token
    ): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        return match($attribute) {
            self::CREATE => true,
            self::DELETE => $this->canDelete($subject, $user)
        };
    }

    private function canDelete (Comment|ReviewComment $comment, User $user): bool
    {
        return $user === $comment->getUser();
    }
}