<?php

namespace App\Security;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    const CREATE = 'createPost';
    const updateOrDelete = 'updateOrDelete';
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::CREATE, self::updateOrDelete])) {
            return false;
        }

        if ($attribute == self::updateOrDelete && !$subject instanceof Post) {
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
            self::updateOrDelete => $this->canUpdateOrDelete($subject, $user)
        };
    }

    private function canUpdateOrDelete (Post $post, User $user): bool
    {
        return $user === $post->getUser();
    }
}