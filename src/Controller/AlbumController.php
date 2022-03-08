<?php

namespace App\Controller;

use App\Repository\TravelRepository;
use App\Repository\UserRepository;
use App\Services\AlbumService;
use App\Services\RequestService;
use App\Services\ResponseService;
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

class AlbumController extends HelloworldController
{
    // ------------------------ >

    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        private NormalizerInterface $normalizer,
        private AlbumService $albumService,
        private TravelRepository $travelRepository,
        private UserRepository $userRepository
    ) {
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    // ------------------------ >

    /**
     * @Route("/albums", name="create_album", methods={ "POST" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function addAction(Request $request): Response
    {
        $parameters = $this->getContent($request);
        $loggedUser = $this->getLoggedUser($this->userRepository);

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $errors = $this->validate($parameters, [
            'title' => [new Type(['type' => 'string']), new NotBlank()],
            'description' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'travelId' => [new Type(['type' => 'int']), new NotBlank()],
        ]);

        if (!empty($errors)) {
            return $errors;
        }

        $travel = $this->travelRepository->find($parameters['travelId']);

        $album = $this->albumService->create(
            $parameters['title'],
            $parameters['description'],
            $travel
        );

        $albumNormalized = $this->normalizer->normalize($album, null, ['groups' => 'album.by.current']);

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $albumNormalized, $loggedUser);
    }

    /**
     * @Route("/albums", name="get_all_album", methods={ "GET" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getAllAction(): Response
    {
        $loggedUser = $this->getLoggedUser($this->userRepository);

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $albums = $this->albumService->getAll();
        $albumsNormalized = $this->normalizer->normalize($albums, null, ['groups' => 'album.by.current']);

        return $this->buildSuccessResponse(Response::HTTP_OK, $albumsNormalized, $loggedUser);
    }

    /**
     * @Route("/albums/{uuid}", name="get_album_by_uuid", methods={ "GET" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getByUUidAction(string $uuid): Response
    {
        $loggedUser = $this->getLoggedUser($this->userRepository);

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $album = $this->albumService->getByUuid($uuid);
        $albumNormalized = $this->normalizer->normalize($album, null, ['groups' => 'album.by.current']);

        return $this->buildSuccessResponse(Response::HTTP_OK, $albumNormalized, $loggedUser);
    }

    /**
     * @Route("/albums/{uuid}", name="delete_album", methods={ "DELETE" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function deleteAction(string $uuid): Response
    {
        $loggedUser = $this->getLoggedUser($this->userRepository);
        // No logged used
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $album = $this->albumService->getByUuid($uuid);
        if (null === $album) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'album.notFound', 'L\'album est introuvable');
        }
        $albumDeleted = $this->albumService->delete($album);
        $albumNormalized = $this->normalizer->normalize($albumDeleted, null, ['groups' => 'album.by.current']);

        return $this->buildSuccessResponse(Response::HTTP_OK, $albumNormalized, $loggedUser);
    }

    /**
     * @Route("/albums/{uuid}", name="album_update", methods={ "PUT" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function updateAction(Request $request, string $uuid): Response
    {
        $parameters = $this->getContent($request);
        $loggedUser = $this->getLoggedUser($this->userRepository);

        // No logged used
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $album = $this->albumService->getByUuid($uuid);
        if (null === $album) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'album.notFound', 'L\'album est introuvable');
        }

        $errors = $this->validate($parameters, [
            'title' => [new Type(['type' => 'string']), new NotBlank()],
            'description' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
        ]);

        // Validation errors
        if (!empty($errors)) {
            return $errors;
        }

        $albumUpdated = $this->albumService->update(
            $album,
            $parameters['title'],
            $parameters['description']
        );

        $albumNormalized = $this->normalizer->normalize($albumUpdated, null, ['groups' => 'album.by.current']);

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $albumNormalized, $loggedUser);
    }
}
