<?php

namespace App\Controller\Auth;

use App\Controller\HelloworldController;
use App\Entity\User;
use App\Services\RequestService;
use App\Services\ResponseService;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginController extends HelloworldController
{
    // ------------------------ >

    public function __construct(
        ResponseService      $responseService,
        RequestService       $requestService,
        ValidatorInterface   $validator,
        NormalizerInterface  $normalizer,
    )
    {
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    // ------------------------ >

    /**
     * @Route("/auth/login", name="login", methods={ "POST" })
     *
     * @throws Exception
     */
    public function index(#[CurrentUser] ?User $user): Response
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = "Token-test";

        return $this->json([
            'user' => $user->getUserIdentifier(),
            'token' => $token
        ]);
    }
}
