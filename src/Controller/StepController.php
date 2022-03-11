<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\PlaceRepository;
use App\Repository\StepRepository;
use App\Repository\TravelRepository;
use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\StepService;
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

class StepController extends HelloworldController
{
    // ------------------------ >

    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        private StepService $stepService,
        private TravelRepository $travelRepository,
        private AlbumRepository $albumRepository,
        private PlaceRepository $placeRepository,
        private StepRepository $stepRepository,
    ) {
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    // ------------------------ >

    /**
     * @Route("/steps", name="create_step", methods={ "POST" })
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
            'travelId' => [new Type(['type' => 'int']), new NotBlank()],
            'placeId' => [new Type(['type' => 'int']), new NotBlank()],
            'albumId' => [new Type(['type' => 'int']), new NotBlank()],
            'startedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
            'endedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
        ]);

        if (!empty($errors)) {
            return $errors;
        }

        $travel = $this->travelRepository->find($parameters['travelId']);
        $place = $this->placeRepository->find($parameters['placeId']);
        $album = $this->albumRepository->find($parameters['albumId']);
        $startedAt = (array_key_exists('startedAt', $parameters)) ? $this->getDate($request, $parameters['startedAt']) : null;
        $endedAt = (array_key_exists('endedAt', $parameters)) ? $this->getDate($request, $parameters['endedAt']) : null;

        $step = $this->stepService->create(
            $travel,
            $place,
            $album,
            $startedAt,
            $endedAt
        );

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $step, $loggedUser, ['groups' => ['step:read', 'step:nested']]);
    }

    /**
     * @Route("/steps", name="get_all_step", methods={ "GET" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getAllAction(): Response
    {
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $steps = $this->stepRepository->findAllActive();

        return $this->buildSuccessResponse(Response::HTTP_OK, $steps, $loggedUser, ['groups' => ['step:read', 'step:nested']]);
    }

    /**
     * @Route("/steps/{uuid}", name="get_step_by_uuid", methods={ "GET" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getByUUidAction(string $uuid): Response
    {
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $step = $this->stepRepository->findOneByUuid($uuid);

        return $this->buildSuccessResponse(Response::HTTP_OK, $step, $loggedUser, ['groups' => ['step:read', 'step:nested']]);
    }

    /**
     * @Route("/steps/{uuid}", name="delete_step", methods={ "DELETE" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function deleteAction(string $uuid): Response
    {
        $loggedUser = $this->getLoggedUser();

        // No logged used
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $step = $this->stepRepository->findOneByUuid($uuid);

        if (null === $step) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'step.notFound', 'L\'step est introuvable');
        }

        $stepDeleted = $this->stepService->delete($step);

        return $this->buildSuccessResponse(Response::HTTP_OK, $stepDeleted, $loggedUser, ['groups' => ['step:read', 'step:nested']]);
    }

    /**
     * @Route("/steps/{uuid}", name="step_update", methods={ "PUT" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function updateAction(Request $request, string $uuid): Response
    {
        $parameters = $this->getContent($request);
        $loggedUser = $this->getLoggedUser();

        // No logged used
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $step = $this->stepRepository->findOneByUuid($uuid);

        if (null === $step) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'step.notFound', 'L\'step est introuvable');
        }

        $errors = $this->validate($parameters, [
            'albumId' => [new Type(['type' => 'int']), new NotBlank()],
            'placeId' => [new Type(['type' => 'int']), new NotBlank()],
            'startedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
            'endedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
        ]);

        // Validation errors
        if (!empty($errors)) {
            return $errors;
        }
        $album = $this->placeRepository->find($parameters['albumId']);
        $place = $this->placeRepository->find($parameters['placeId']);
        $startedAt = (array_key_exists('startedAt', $parameters)) ? $this->getDate($request, $parameters['startedAt']) : null;
        $endedAt = (array_key_exists('endedAt', $parameters)) ? $this->getDate($request, $parameters['endedAt']) : null;

        $stepUpdated = $this->stepService->update(
            $step,
            $album,
            $place,
            $startedAt,
            $endedAt
        );

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $stepUpdated, $loggedUser, ['groups' => ['step:read', 'step:nested']]);
    }
}
