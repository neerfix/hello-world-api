<?php

namespace App\Controller;

use App\Services\ResponseService;
use App\Services\SecurityService;
use App\Services\TravelService;
use App\Services\UserService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TravelController extends HelloworldController
{
    // ------------------------ >

    public function __construct(
        SecurityService $securityService,
        UserService $userService,
        ResponseService $responseService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        private TravelService $travelService
    ) {
        parent::__construct($securityService, $userService, $responseService, $validator, $normalizer);
    }

    // ------------------------ >

    /**
     * @Route("/travel", name="create_travel", methods={ "POST" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function addAction(Request $request): Response
    {
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (empty($loggedUser)) {
            //FIXME remove // when front is ready
            // return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $errors = $this->validate($request->request->all(), [
            'email' => [new Type(['type' => 'string']), new NotBlank()],
            'password' => [new Type(['type' => 'string']), new NotBlank()],
            'recaptchaResponse' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
        ]);

        if (!empty($errors)) {
            return $errors;
        }

//        $travel = $this->travelService->create(
//
//        );

        $this->buildSuccessResponse(Response::HTTP_CREATED, '$travel', $loggedUser);
    }
}
