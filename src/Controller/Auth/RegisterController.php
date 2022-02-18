<?php

namespace App\Controller\Auth;

use App\Controller\HelloworldController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\UserService;
use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\SecurityService;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class RegisterController extends HelloworldController
{
    /**
     * @Route("/auth/register", name="register", methods={ "POST" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */

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
    // @TODO: A changer en JSONResponse quand buildSuccess
    public function registerAction(Request $request): Response
    {
        
        $errors = $this->validator->validate($request->request->all(), [
            'email' => [new Type(['type' => 'string']), new NotBlank()],
            'username' => [new Type(['type' => 'string']), new NotBlank()],
            'password' => [new Type(['type' => 'string']), new NotBlank()],
            'birthdate' => [new DateTime(['format' => 'Y-m-d'])],
            'firstname' => [new Type(['type' => 'string']), new NotBlank()],
            'lastname' => [new Type(['type' => 'string']), new NotBlank()],
        ]);

        if(empty($errors)) {
            $requestBody = $request->request->all();
            $userService->create(
                $requestBody->email,
                $requestBody->username,
                $request->password,
                $request->birthdate,
                $request->firstname,
                $request->lastname
            );
            return $this->buildSuccessResponse(Response::HTTP_OK,$user);
        }
        else { 
            return $this->responseService->error422($errors);
        }

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RegisterController.php',
        ]);
    }
}
