<?php

namespace App\Controller;

use App\Repository\PlaceRepository;
use App\Repository\UserRepository;
use App\Repository\WishListRepository;
use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\WishListService;
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

class WishListController extends HelloworldController
{
    // ------------------------ >

    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        private WishListService $wishListService,
        private PlaceRepository $placeRepository,
        private UserRepository $userRepository,
        private WishListRepository $wishListRepository
    ) {
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    // ------------------------ >

    /**
     * @Route("/wishlists", name="create_wishlists", methods={ "POST" })
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
            'description' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'placeId' => [new Optional([new Type(['type' => 'int']), new NotBlank()])],
            'userId' => [new Optional([new Type(['type' => 'int']), new NotBlank()])],
            'estimatedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
        ]);

        if (!empty($errors)) {
            return $errors;
        }

        $place = $this->placeRepository->find($parameters['placeId']);
        if (null === $place) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'place.notFound', 'Le lieu est introuvable');
        }

        $user = $this->userRepository->find($parameters['userId']);
        if (null === $user) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'user.notFound', 'L\'utilisateur est introuvable');
        }

        $estimatedAt = (array_key_exists('estimatedAt', $parameters)) ? $this->getDate($request, $parameters['estimatedAt']) : null;
        $description = (array_key_exists('description', $parameters)) ? $parameters['description'] : null;

        $wishList = $this->wishListService->create(
            $parameters['name'],
            $place,
            $user,
            $description,
            $estimatedAt
        );

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $wishList, $loggedUser, ['groups' => ['wishList:read', 'wishList:nested']]);
    }

    /**
     * @Route("/wishlists", name="get_all_wishlist", methods={ "GET" })
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

        $wishLists = $this->wishListRepository->findAllActive();

        return $this->buildSuccessResponse(Response::HTTP_OK, $wishLists, $loggedUser, ['groups' => ['wishList:read', 'wishList:nested']]);
    }

    /**
     * @Route("/wishlists/{uuid}", name="get_wishlist_by_uuid", methods={ "GET" })
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

        $wishList = $this->wishListRepository->findOneByUuid($uuid);
        if (null === $wishList) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'wishList.notFound', 'La liste de souhait est introuvable');
        }

        return $this->buildSuccessResponse(Response::HTTP_OK, $wishList, $loggedUser, ['groups' => ['wishList:read', 'wishList:nested']]);
    }

    /**
     * @Route("/wishlists/{uuid}", name="delete_wishlist", methods={ "DELETE" })
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

        $wishList = $this->wishListRepository->findOneByUuid($uuid);
        if (null === $wishList) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'wishList.notFound', 'La liste de souhait est introuvable');
        }

        $wishListDeleted = $this->wishListService->delete($wishList);

        return $this->buildSuccessResponse(Response::HTTP_OK, $wishListDeleted, $loggedUser, ['groups' => ['wishList:read', 'wishList:nested']]);
    }

    /**
     * @Route("/wishlists/{uuid}", name="wishlist_update", methods={ "PUT" })
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

        $wishList = $this->wishListRepository->findOneByUuid($uuid);
        if (null === $wishList) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'wishList.notFound', 'La liste de souhait est introuvable');
        }

        $errors = $this->validate($parameters, [
            'name' => [new Type(['type' => 'string']), new NotBlank()],
            'description' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'placeId' => [new Optional([new Type(['type' => 'int']), new NotBlank()])],
            'userId' => [new Optional([new Type(['type' => 'int']), new NotBlank()])],
            'estimatedAt' => [new Optional([new DateTime(['format' => 'Y-m-d']), new NotBlank()])],
        ]);

        if (!empty($errors)) {
            return $errors;
        }

        $place = $this->placeRepository->find($parameters['placeId']);
        if (null === $place) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'place.notFound', 'Le lieu est introuvable');
        }

        $user = $this->userRepository->find($parameters['userId']);
        if (null === $user) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'user.notFound', 'L\'utilisateur est introuvable');
        }

        $estimatedAt = (array_key_exists('estimatedAt', $parameters)) ? $this->getDate($request, $parameters['estimatedAt']) : null;
        $description = (array_key_exists('description', $parameters)) ? $parameters['description'] : null;

        $wishListUpdated = $this->wishListService->update(
            $wishList,
            $parameters['name'],
            $place,
            $user,
            $description,
            $estimatedAt,
        );

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $wishListUpdated, $loggedUser, ['groups' => ['wishList:read', 'wishList:nested']]);
    }
}
