<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\RequestService;
use App\Services\ResponseService;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class HelloworldController extends AbstractController
{
    // ------------------------------ >

    public function __construct(
        protected ResponseService $responseService,
        protected RequestService $requestService,
        ValidatorInterface $validator,
        NormalizerInterface $serializer,
    ) {
        parent::__construct(
            $validator,
            $serializer,
            $this->responseService,
            $this->requestService
        );
    }

    /* ***** Identification ***** */

    /**
     * Override parent function to type hint return.
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLoggedUser(UserRepository $userRepository): ?User
    {
        $user = parent::getUser();

        //Fixme It's tmp for development, remove it.
        $neerfix = $userRepository->findOneByEmail('nicolas.notararigo@gmail.com');

        return $neerfix;

        // No user
        if (empty($user) || !($user instanceof User)) {
            return null;
        }

        return $user;
    }

    /**
     * @throws JsonException
     */
    public function getContent(Request $request): array
    {
        return json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}
