<?php

namespace App\Controller\Auth;

use App\Controller\HelloworldController;
use App\Entity\Token;
use App\Entity\User;
use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\TokenService;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginController extends HelloworldController
{
    // ------------------------ >


    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        private TokenService $tokenService,
    ) {
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    // ------------------------ >

    /**
     * @Route("/auth/login", name="app_login", methods={ "POST" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function index(#[CurrentUser] ?User $user)
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $accessToken = $this->tokenService->create($user, Token::TARGET_ACCESS_TOKEN);
        $refreshToken = $this->tokenService->create($user, Token::TARGET_REFRESH_TOKEN, new DateTime('+1 year'));

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, [
            'user' => $user->getUserIdentifier(),
            'accessToken' => $accessToken->getValue(),
            'refreshToken' => $refreshToken->getValue(),
        ], $user);
    }
}
