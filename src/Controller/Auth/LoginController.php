<?php

namespace App\Controller\Auth;

use App\Controller\HelloworldController;
use App\Services\LoginService;
use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\SecurityService;
use App\Services\UserService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LoginController extends HelloworldController
{
    // ------------------------ >

    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        private LoginService $loginService,
        private UserService $userService
    ) {
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    // ------------------------ >

    /**
     * @Route("/auth/login", name="login", methods={ "POST" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function loginAction(Request $request): Response
    {
        $content = $request->getContent();
        $parameters = json_decode($content, true);

        $errors = $this->validate($parameters, [
            'email' => [new Type(['type' => 'string']), new NotBlank()],
            'password' => [new Type(['type' => 'string']), new NotBlank()],
        ]);

        if (!empty($errors)) {
            return $errors;
        }
        $user = $this->userService->login($parameters["email"],$parameters["password"]);
//
        $this->loginService->create($user, "V1", $request->getClientIp(), $request->headers->get('User-Agent'), true);

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $user);
    }
}
