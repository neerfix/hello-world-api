<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PlaceRepository;
use App\Repository\TravelRepository;
use App\Services\PlaceService;
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
        NormalizerInterface $normalizer,
        private TravelService $travelService,
        private TravelRepository $travelRepository,
        private PlaceRepository $placeRepository,
        private PlaceService $placeService
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
        $travel = $this->travelRepository->findAllActive();

        return $this->buildSuccessResponse(Response::HTTP_OK, $travel, $loggedUser, ['groups' => 'travel:read']);
    }

    /**
     * @Route("/travels/{uuid}", name="get_travel", methods={ "GET" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getAction(string $uuid): Response
    {
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $travel = $this->travelRepository->findOneBy(['uuid' => $uuid]);

        if (null === $travel) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'not.found', 'Le voyage demandé n\'est pas trouvé');
        }

        return $this->buildSuccessResponse(Response::HTTP_OK, $travel, $loggedUser, ['groups' => ['travel:read', 'travel:nested']]);
    }

    /**
     * @Route("/travels/{uuid}", name="delete_travel", methods={ "DELETE" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function deleteAction(string $uuid): Response
    {
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $travel = $this->travelRepository->findOneBy(['uuid' => $uuid]);

        if (null === $travel) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'not.found', 'Le voyage demandé n\'est pas trouvé');
        }

        $roles = $loggedUser->getRoles();

        if (!in_array(User::ROLE_ADMIN, $roles, true) && $travel->getUserId() !== $loggedUser->getId()) {
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
        $placeRequest = $parameters['place'];

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $travel = $this->travelRepository->findOneBy(['uuid' => $uuid]);

        if (null === $travel) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'not.found', 'Le voyage demandé n\'est pas trouvé');
        }

        $roles = $loggedUser->getRoles();

        if (!in_array(User::ROLE_ADMIN, $roles, true) && $travel->getUserId() !== $loggedUser->getId()) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $errors = $this->validate($parameters, [
            'name' => [new Type(['type' => 'string']), new NotBlank()],
            'budget' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'description' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'startedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
            'endedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
            'isSharable' => [new Optional([new Type(['type' => 'bool']), new NotBlank()])],
        ]);

        if (!empty($errors)) {
            return $errors;
        }

        $errorsPlace = $this->validate($placeRequest, [
            'name' => [new Type(['type' => 'string']), new NotBlank()],
            'latitude' => [new Type(['type' => 'float']), new NotBlank()],
            'longitude' => [new Type(['type' => 'float']), new NotBlank()],
        ]);

        if (!empty($errorsPlace)) {
            return $errors;
        }

        $place = $this->placeService->update(
            $travel->getPlaceId(),
            $placeRequest['name'],
            $placeRequest['latitude'],
            $placeRequest['longitude']
        );

        $startedAt = $this->getDate($request, $request->request->get('startedAt'));
        $endedAt = $this->getDate($request, $request->request->get('endedAt'));
        $budget = (array_key_exists('budget', $parameters)) ? $parameters['budget'] : null;
        $description = (array_key_exists('description', $parameters)) ? $parameters['description'] : null;
        $isSharable = (array_key_exists('isSharable', $parameters)) ? $parameters['isSharable'] : null;

        $travelUpdated = $this->travelService->update(
            $travel,
            $loggedUser,
            $place,
            $parameters['name'],
            $budget,
            $startedAt,
            $endedAt,
            $description,
            $isSharable
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
        $placeRequest = $parameters['place'];

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $errors = $this->validate($parameters, [
            'name' => [new Type(['type' => 'string']), new NotBlank()],
            'budget' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'description' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'startedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
            'endedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
            'isSharable' => [new Optional([new Type(['type' => 'bool']), new NotBlank()])],
        ]);

        if (!empty($errors)) {
            return $errors;
        }

        $errorsPlace = $this->validate($placeRequest, [
            'name' => [new Type(['type' => 'string']), new NotBlank()],
            'latitude' => [new Type(['type' => 'float']), new NotBlank()],
            'longitude' => [new Type(['type' => 'float']), new NotBlank()],
            'address' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'city' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'zipcode' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'country' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
        ]);

        if (!empty($errorsPlace)) {
            return $errorsPlace;
        }

        $placeAddress = (isset($placeRequest['address'])) ? $placeRequest['address'] : null;
        $placeCity = (isset($placeRequest['city'])) ? $placeRequest['city'] : null;
        $placeZipcode = (isset($placeRequest['zipcode'])) ? $placeRequest['zipcode'] : null;
        $placeCountry = (isset($placeRequest['country'])) ? $placeRequest['country'] : null;

        $place = $this->placeService->create($placeRequest['name'], $placeRequest['latitude'], $placeRequest['longitude'], $placeAddress, $placeCity, $placeZipcode, $placeCountry);

        $startedAt = $this->getDate($request, $request->request->get('startedAt'));
        $endedAt = $this->getDate($request, $request->request->get('endedAt'));
        $budget = (array_key_exists('budget', $parameters)) ? $parameters['budget'] : null;
        $description = (array_key_exists('description', $parameters)) ? $parameters['description'] : null;
        $isSharable = (array_key_exists('isSharable', $parameters)) ? $parameters['isSharable'] : null;

        $travel = $this->travelService->create(
            $loggedUser,
            $place,
            $parameters['name'],
            $budget,
            $startedAt,
            $endedAt,
            $description,
            $isSharable
        );

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $travel, $loggedUser, ['groups' => ['travel:read', 'travel:nested']]);
    }
}
