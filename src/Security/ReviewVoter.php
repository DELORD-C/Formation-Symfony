<?php

namespace App\Security;

use App\Entity\Review;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewVoter extends Voter
{
    const SUPPORTS = ['CREATE-REVIEW', 'READ-REVIEW'];
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

        $review = $subject;

        return match($attribute) {
            self::SUPPORTS[0] => $this->canCreate($user),
            self::SUPPORTS[1] => true,
            self::SUPPORTS_WITH_SUBJECT[0], self::SUPPORTS_WITH_SUBJECT[1] => $this->canUpdateOrDelete($user, $review)
        };
    }

    private function canCreate(?UserInterface $user): bool
    {
        return $user instanceof User;
    }

    private function canUpdateOrDelete(?UserInterface $user, Review $review): bool
    {
        return $user === $review->getUser();
    }

}