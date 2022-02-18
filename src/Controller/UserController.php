<?php

use App\Controller\HelloworldController;
use App\Controller\SecurityService;
use App\Controller\UserService;
use App\Repository\UserRepository;
use App\Services\RequestService;
use App\Services\ResponseService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends HelloworldController
{
    // --------------------- >

    public function __construct(
        SecurityService $securityService,
        UserService $userService,
        RequestService $requestService,
        ResponseService $responseService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        private UserRepository $userRepository
    ) {
        parent::__construct($securityService, $userService, $requestService, $responseService, $validator, $normalizer);
    }

    // --------------------- >

    // ------------------------------ >

    /**
     * @Route("/users/me", name="get_me", methods={ "GET" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function getMeAction(): JsonResponse
    {
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (empty($loggedUser)) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        return $this->buildSuccessResponse(Response::HTTP_OK, $loggedUser, $loggedUser);
    }

    /**
     * @Route("/users", name="get_all_users", methods={ "GET" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function getAllAction(): JsonResponse
    {
        $loggedUser = $this->getLoggedUser();

        // No logged used
        if (empty($loggedUser)) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $users = $this->userRepository->findAll();

        return $this->buildSuccessResponse(Response::HTTP_OK, $users, $loggedUser);
    }
}
