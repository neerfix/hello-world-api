<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use App\Repository\UserRepository;
use App\Services\PlaceService;
use App\Services\RequestService;
use App\Services\ResponseService;
use Exception;
use ProxyManager\Exception\ExceptionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlaceController extends HelloworldController
{
    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        private PlaceService $placeService,
        private UserRepository $userRepository,
        private PlaceRepository $placeRepository
    ) {
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    /**
     * @Route("/places", name="create_places", methods={ "POST" })
     *
     * @throws ExceptionInterface
     * @throws Exception
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
            'address' => [new Type(['type' => 'string']), new NotBlank()],
            'city' => [new Type(['type' => 'string']), new NotBlank()],
            'zipcode' => [new Type(['type' => 'string']), new NotBlank()],
            'country' => [new Type(['type' => 'string']), new NotBlank()],
            'name' => [new Type(['type' => 'string']), new NotBlank()],
            'latitude' => [new Type(['type' => 'float']), new NotBlank()],
            'longitude' => [new Type(['type' => 'float']), new NotBlank()],
        ]);

        if (!empty($errors)) {
            return $errors;
        }

        $place = $this->placeService->create(
            $parameters['address'],
            $parameters['city'],
            $parameters['zipcode'],
            $parameters['country'],
            $parameters['name'],
            $parameters['latitude'],
            $parameters['longitude']
        );

        $placeNormalizer = $this->normalizer->normalize($place, null, ['groups' => ['place:read']]);

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $placeNormalizer, $loggedUser);
    }

    /**
     * @Route("/places", name="get_all_places", methods={"GET"})
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getAllAction()
    {
        $loggedUser = $this->getLoggedUser();
        $places = $this->placeRepository->findAll();

        return $this->buildSuccessResponse(Response::HTTP_OK, $places, $loggedUser);
    }

    /**
     * @Route("/places/{uuid}", name="get_place", methods={"GET"})
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getAction(Request $request, string $uuid): Response
    {
        $user = $this->getLoggedUser();

        if (null === $user) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $place = $this->placeService->getByUuid($uuid);

        if (null === $place) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'not.found', 'La localisation n\'a pas été trouvée');
        }

        $placeNormalizer = $this->normalizer->normalize($place, null, ['groups' => ['place:read']]);

        return $this->buildSuccessResponse(Response::HTTP_OK, $placeNormalizer, $user);
    }
}
