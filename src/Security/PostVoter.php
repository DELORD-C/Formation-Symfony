<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter {

    const ATTR = ['UPDATE', 'READ', 'DELETE'];
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::ATTR)) {
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

        /** @var Post $post */
        $post = $subject;

        return match($attribute) {
            'UPDATE', 'DELETE' => $this->canUpdate($post, $user),
            'READ' => true

        };
    }

    private function canUpdate (Post $post, User $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles()) || $post->getUser() === $user;
    }
}