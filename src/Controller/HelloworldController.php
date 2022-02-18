<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\RequestService;
use App\Services\ResponseService;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class HelloworldController extends AbstractController
{
    // ------------------------------ >

    public function __construct(
        protected SecurityService $securityService,
        protected UserService $userService,
        protected RequestService $requestService,
        protected ResponseService $responseService,
        ValidatorInterface $validator,
        NormalizerInterface $serializer,
    ) {
        parent::__construct(
            $validator,
            $serializer,
            $this->requestService,
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
