<?php

namespace App\Controller;

use App\Services\RequestService;
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
use Symfony\Component\Validator\Constraints\DateTime;
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
        RequestService $requestService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        private TravelService $travelService
    ) {
        parent::__construct($securityService, $userService, $responseService, $requestService, $validator, $normalizer);
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
        if (null === $loggedUser) {
            //FIXME remove // when front is ready
            // return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $errors = $this->validate($request->request->all(), [
            'name' => [new Type(['type' => 'string']), new NotBlank()],
            'budget' => [new Type(['type' => 'float']), new NotBlank()],
            'description' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'startedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
            'endedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
            'isSharable' => [new Type(['type' => 'bool']), new NotBlank()],
        ]);

        if (!empty($errors)) {
            return $errors;
        }

        $startedAt = $this->getDate($request, $request->request->get('startedAt'));
        $endedAt = $this->getDate($request, $request->request->get('endedAt'));

        $travel = $this->travelService->create(
            $loggedUser,
            $request->request->get('name'),
            $request->request->get('budget'),
            $startedAt,
            $endedAt,
            $request->request->get('description'),
            $request->request->get('isSharable')
        );

        $this->buildSuccessResponse(Response::HTTP_CREATED, $travel, $loggedUser);
    }
}
