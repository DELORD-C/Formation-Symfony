<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PostVoter extends Voter {
    const CASES = ['EDITPOST', 'EDIT', 'SHOW', 'DELETE'];

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::CASES)) {
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
            'CREATE', 'SHOW' => true,
            'EDIT', 'DELETE' => $this->canEdit($post, $user)
        };
    }

    private function canEdit(Post $post, User $user): bool
    {
        return (
            in_array('ROLE_ADMIN', $user->getRoles()) ||
            $user === $post->getUser()
        );
    }
}