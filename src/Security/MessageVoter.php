<?php

namespace App\Security;

use App\Entity\Message;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MessageVoter extends Voter {
    const CASES = ['CREATE', 'EDIT', 'SHOW', 'DELETE', 'REPLY'];

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::CASES)) {
            return false;
        }
        if (!$subject instanceof Message) {
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

        $message = $subject;

        return match($attribute) {
            'CREATE', 'SHOW' => true,
            'EDIT', 'DELETE'=> $this->canEdit($message, $user),
            'REPLY' => $this->canReply($message, $user)
        };
    }

    private function canEdit(Message $message, User $user): bool
    {
        return (
            in_array('ROLE_ADMIN', $user->getRoles()) ||
            $user === $message->getSender()
        );
    }

    private function canReply(Message $message, User $user): bool
    {
        return (
            $user === $message->getSender() ||
            $message->getTarget()->contains($user)
        );
    }
}