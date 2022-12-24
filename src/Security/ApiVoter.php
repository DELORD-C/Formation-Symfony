<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ApiVoter extends Voter {
    const CASES = ['API'];
    private RequestStack $requestStack;
    private UserRepository $repository;

    public function construct (RequestStack $requestStack, UserRepository $userRepository) {
        $this->requestStack = $requestStack;
        $this->repository = $userRepository;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, self::CASES)) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        return true;
    }
}