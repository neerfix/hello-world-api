<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\ResponseService;
use App\Services\SecurityService;
use App\Services\UserService;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class HelloworldController extends AbstractController
{
    // ------------------------------ >

    public function __construct(
        protected SecurityService $securityService,
        protected UserService $userService,
        protected ResponseService $responseService,
        ValidatorInterface $validator,
        NormalizerInterface $serializer,
    ) {
        parent::__construct(
            $validator,
            $serializer,
            $this->responseService
        );
    }

    /* ***** Identification ***** */

    /**
     * Override parent function to type hint return.
     */
    public function getLoggedUser(): ?User
    {
        $user = parent::getUser();

        // No user
        if (empty($user) || !($user instanceof User)) {
            return null;
        }

        return $user;
    }
}
