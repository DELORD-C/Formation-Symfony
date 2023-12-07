<?php

namespace App\Security;
use App\Entity\Review;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ReviewVoter extends Voter
{
    const CREATE = 'createReview';
    const EDITORDELETE = 'editOrDelete';
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::CREATE, self::EDITORDELETE])) {
            return false;
        }

        if ($attribute == self::EDITORDELETE && !$subject instanceof Review) {
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
            self::EDITORDELETE => $this->canEditOrDelete($subject, $user)
        };
    }

    private function canEditOrDelete (Review $review, User $user): bool
    {
        return $user === $review->getUser();
    }
}