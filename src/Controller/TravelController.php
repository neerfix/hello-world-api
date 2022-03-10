<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TravelRepository;
use App\Repository\UserRepository;
use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\TravelService;
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
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        private NormalizerInterface $normalizer,
        private TravelService $travelService,
        private TravelRepository $travelRepository,
        private UserRepository $userRepository,
    ) {
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    // ------------------------ >

    /**
     * @Route("/travels", name="get_all_travels", methods={ "GET" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function index(): Response
    {
        $loggedUser = $this->getLoggedUser();
        $travel = $this->travelRepository->findAll();

        return $this->buildSuccessResponse(Response::HTTP_OK, $travel, $loggedUser);
    }

    /**
     * @Route("/travels/{uuid}", name="get_travel", methods={ "GET" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getAction(Request $request, string $uuid): Response
    {
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $travel = $this->travelRepository->findOneBy(['uuid' => $uuid]);

        // No logged user
        if (null === $travel) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'not.found', 'Le voyage demandé n\'est pas trouvé');
        }

        $roles = $loggedUser->getRoles();

        // No logged used
        if (!in_array(User::ROLE_ADMIN, $roles, true) || $travel->getUserId() !== $loggedUser->getId()) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        return $this->buildSuccessResponse(Response::HTTP_OK, $travel, $loggedUser, ['groups' => ['travel:read', 'travel:nested']]);
    }

    /**
     * @Route("/travels/{uuid}", name="delete_travel", methods={ "DELETE" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function deleteAction(Request $request, string $uuid): Response
    {
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $travel = $this->travelRepository->findOneBy(['uuid' => $uuid]);

        // No logged user
        if (null === $travel) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'not.found', 'Le voyage demandé n\'est pas trouvé');
        }

        $roles = $loggedUser->getRoles();

        // No logged used
        if (!in_array(User::ROLE_ADMIN, $roles, true) || $travel->getUserId() !== $loggedUser->getId()) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $travelDeleted = $this->travelService->delete(
            $travel,
            $loggedUser
        );

        return $this->buildSuccessResponse(Response::HTTP_OK, $travelDeleted, $loggedUser, ['groups' => ['travel:read', 'travel:nested']]);
    }

    /**
     * @Route("/travels/{uuid}", name="update_travel", methods={ "PUT" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function updateAction(Request $request, string $uuid): Response
    {
        $loggedUser = $this->getLoggedUser();
        $parameters = $this->getContent($request);

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $travel = $this->travelRepository->findOneBy(['uuid' => $uuid]);

        // No logged user
        if (null === $travel) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'not.found', 'Le voyage demandé n\'est pas trouvé');
        }

        $roles = $loggedUser->getRoles();

        // No logged used
        if (!in_array(User::ROLE_ADMIN, $roles, true) || $travel->getUserId() !== $loggedUser->getId()) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $errors = $this->validate($parameters, [
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

        $travelUpdated = $this->travelService->update(
            $travel,
            $loggedUser,
            $parameters['name'],
            $parameters['budget'],
            $startedAt,
            $endedAt,
            $parameters['description'],
            $parameters['isSharable']
        );

        return $this->buildSuccessResponse(Response::HTTP_OK, $travelUpdated, $loggedUser, ['groups' => ['travel:read', 'travel:nested']]);
    }

    /**
     * @Route("/travels", name="create_travel", methods={ "POST" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function addAction(Request $request): Response
    {
        $parameters = $this->getContent($request);
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $errors = $this->validate($parameters, [
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
            $parameters['name'],
            $parameters['budget'],
            $startedAt,
            $endedAt,
            $parameters['description'],
            $parameters['isSharable']
        );

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $travel, $loggedUser, ['groups' => ['travel:read', 'travel:nested']]);
    }
}
