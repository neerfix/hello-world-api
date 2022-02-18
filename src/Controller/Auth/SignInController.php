<?php

namespace App\Controller\Auth;


use App\Controller\HelloworldController;
use App\Services\ResponseService;
use App\Services\SecurityService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SignInController extends HelloworldController
{

    public function __construct(
        SecurityService $securityService,
        UserService $userService,
        ResponseService $responseService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
    ) {
        parent::__construct($securityService, $userService, $responseService, $validator, $normalizer);
    }

    /**
     * @Route("/auth/login", name="login", methods={ "POST" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
//    public function loginAction(Request $request): Response
//    {
//        return $this->buildSuccessResponse(Response::HTTP_OK);
//    }
}
