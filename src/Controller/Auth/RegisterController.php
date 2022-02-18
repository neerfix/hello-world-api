<?php

namespace App\Controller\Auth;

use App\Controller\HelloworldController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\UserService;
use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\SecurityService;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class RegisterController extends HelloworldController
{
    
    public function __construct(
        SecurityService $securityService,
        UserService $userService,
        RequestService $requestService,
        ResponseService $responseService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
    ) {
        parent::__construct($securityService, $userService, $requestService, $responseService, $validator, $normalizer);
    }
    /**
     * @Route("/auth/register", name="register", methods={ "POST" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function registerAction(Request $request): JSONResponse
    {
        
        $errors = $this->validate($request->request->all(), [
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

        $requestBody = $request->request->all();
            $this->userService->create(
                $requestBody->email,
                $requestBody->username,
                $request->password,
                $request->birthdate,
                $request->firstname,
                $request->lastname
            );
        return $this->buildSuccessResponse(Response::HTTP_OK,$user);
    }
}
