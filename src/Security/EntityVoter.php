<?php

namespace App\Security;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Review;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EntityVoter extends Voter
{
    const SUPPORTS = ['CREATE', 'READ'];
    const SUPPORTS_WITH_SUBJECT = ['UPDATE', 'DELETE'];

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::SUPPORTS) &&
            !in_array($attribute, self::SUPPORTS_WITH_SUBJECT)) {
            return false;
        }

        if (in_array($attribute, self::SUPPORTS_WITH_SUBJECT) && !$subject instanceof Review) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        return match($attribute) {
            self::SUPPORTS[0] => $this->canCreate($user),
            self::SUPPORTS[1] => true,
            self::SUPPORTS_WITH_SUBJECT[0] => $this->canUpdate($user, $subject),
            self::SUPPORTS_WITH_SUBJECT[1] => $this->canDelete($user, $subject)
        };
    }

    private function canCreate(?UserInterface $user): bool
    {
        return $user instanceof User;
    }

    private function canUpdate(?UserInterface $user, Post|Review|Comment $subject): bool
    {
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        return $user === $subject->getUser();
    }

    private function canDelete(?UserInterface $user, Post|Review|Comment $subject): bool
    {

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }


        if ($subject instanceof Comment) {
            return $user === $subject->getUser() || $user === $subject->getPost()->getUser();
        }

        return $user === $subject->getUser();
    }

}