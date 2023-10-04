<?php

namespace App\Security;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter {

    const ATTR = ['UPDATE', 'DELETE'];
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::ATTR)) {
            return false;
        }

        if (!$subject instanceof Comment) {
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

        /** @var Comment $comment */
        $comment = $subject;

        return match($attribute) {
            'UPDATE' => $this->canUpdate($comment, $user),
            'DELETE' => $this->canDelete($comment, $user)

        };
    }

    private function canUpdate (Comment $comment, User $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles()) || $comment->getUser() === $user;
    }

    private function canDelete (Comment $comment, User $user): bool
    {
        return
            in_array('ROLE_ADMIN', $user->getRoles())
            || $comment->getUser() === $user
            || $comment->getPost()->getUser() === $user;
    }
}