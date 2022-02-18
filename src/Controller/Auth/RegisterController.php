<?php

namespace App\Controller\Auth;

use App\Controller\HelloworldController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\UserService;
use App\Services\ResponseService;
use App\Services\SecurityService;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class RegisterController extends HelloworldController
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
     * @Route("/auth/register", name="register", methods={ "POST" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function registerAction(Request $request): Response
    {
        $requestBody = $request->request->all();
        $errors = $this->validate($requestBody, [
            'email' => [new Type(['type' => 'string']), new NotBlank()],
            'username' => [new Type(['type' => 'string']), new NotBlank()],
            'password' => [new Type(['type' => 'string']), new NotBlank()],
            'birthdate' => [new DateTime(['format' => 'Y-m-d'])],
            'firstname' => [new Type(['type' => 'string']), new NotBlank()],
            'lastname' => [new Type(['type' => 'string']), new NotBlank()],
        ]);

        // if(!empty($errors)) {
        //     return $errors;
        // }

        $user = $this->userService->create(
                $request->request->get("email"),
                $request->request->get("username"),
                $request->request->get("password"),
                \DateTime::createFromFormat("Y-m-d",$request->request->get("birthdate")),
                $request->request->get("firstname"),
                $request->request->get("lastname")
            );
        return $this->buildSuccessResponse(Response::HTTP_OK,$user);
    }
}
