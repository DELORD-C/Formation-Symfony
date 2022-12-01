<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter {
    const CREATE = 'create';
    const EDIT = 'edit';
    const SHOW = 'show';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::CREATE, self::EDIT, self::SHOW])) {
            return false;
        }

        if (!$subject instanceof Post) {
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

        $post = $subject;

        return match($attribute) {
            self::CREATE, self::SHOW => true,
            self::EDIT => $this->canEdit($post, $user)
        };
    }

    private function canEdit(Post $post, User $user): bool
    {
       return (
           in_array('ROLE_ADMIN', $user->getRoles()) ||
           $user === $post->getUser());
    }
}