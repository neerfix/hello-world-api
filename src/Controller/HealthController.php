<?php

namespace App\Controller;

use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\TokenService;
use App\Services\UserService;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HealthController extends HelloworldController
{
    // ------------------------------ >

    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        private UserService $userService,
        private TokenService $tokenService,
        private MailerService $mailerService,
        private TokenRepository $tokenRepository,
        private UserRepository $userRepository,
    ) {
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    // ------------------------------ >

    /**
     * @Route("/", name="health", methods={ "GET" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function indexAction(): Response
    {
        return $this->buildSuccessResponse(Response::HTTP_OK, ['message' => 'Welcome to the new World.']);
    }
}
