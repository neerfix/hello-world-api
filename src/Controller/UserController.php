<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\SecurityService;
use App\Services\UserService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends HelloworldController
{
    // ------------------------------ >

    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        private NormalizerInterface $normalizer,
        private UserService $userService,
        private UserRepository $userRepository,
        private SecurityService $securityService,
    ) {
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    // ------------------------------ >

    /**
     * @Route("/users/me", name="get_me", methods={ "GET" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function getMeAction(): JsonResponse
    {
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        return $this->buildSuccessResponse(Response::HTTP_OK, $loggedUser, $loggedUser, ['groups' => 'user:read']);
    }

    /**
     * @Route("/users", name="get_all_users", methods={ "GET" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function getAllAction(): JsonResponse
    {
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $roles = $loggedUser->getRoles();

        // No logged used
        if (in_array(User::ROLE_ADMIN, $roles, true)) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $users = $this->userRepository->findAllActive();

        return $this->buildSuccessResponse(Response::HTTP_OK, $users, $loggedUser, ['groups' => 'user:read']);
    }

    /**
     * @Route("/users", name="auth_signup_email", methods={ "POST" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function userSignup(Request $request): JsonResponse
    {
        $parameters = $this->getContent($request);

        $errors = $this->validate($parameters, [
            'email' => [new Type(['type' => 'string']), new NotBlank()],
            'password' => [new Type(['type' => 'string']), new NotBlank()],
            'birthDate' => [new DateTime(['format' => 'Y-m-d']), new NotBlank()],
            'username' => [new Type(['type' => 'string'])],
            'firstName' => [new Optional([new Type(['type' => 'string'])])],
            'lastName' => [new Optional([new Type(['type' => 'string'])])],
        ]);

        // Validation errors
        if (!empty($errors)) {
            return $errors;
        }

        $birthDate = $this->getDate($request, $parameters['birthDate']);
        $firstname = $parameters['firstName'] ?? null;
        $lastname = $parameters['lastName'] ?? null;

        $user = $this->userService->create(
            $parameters['email'],
            $parameters['username'],
            $parameters['password'],
            $birthDate,
            $firstname,
            $lastname,
        );

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $user, null, ['groups' => 'user:read']);
    }

    /**
     * @Route("/users/{uuid}", name="get_user", methods={ "GET" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function getUserAction(string $uuid): JsonResponse
    {
        $loggedUser = $this->getLoggedUser();

        $user = $this->userRepository->findOneByUuid($uuid);

        // No logged user
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        if (null === $user) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'user.not_found', 'L\'utilisateur n\'a pas été trouvé');
        }

        return $this->buildSuccessResponse(Response::HTTP_OK, $user, $loggedUser, ['groups' => 'user:read']);
    }

    /**
     * @Route("/users/{uuid}", name="delete_user", methods={ "DELETE" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function deleteUser(string $uuid): JsonResponse
    {
        $loggedUser = $this->getLoggedUser();
        $user = $this->userRepository->findOneByUuid($uuid);

        if (null === $user) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'user.notFound', 'L\'utilisateur est introuvable');
        }

        // No logged used
        if (null === $loggedUser || ($this->securityService->isSameUser($loggedUser, $uuid) && !$this->securityService->isAdmin($loggedUser))) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $userDeleted = $this->userService->delete($user, $loggedUser);

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $userDeleted, $loggedUser, ['groups' => 'user:read']);
    }

    /**
     * @Route("/users/{uuid}", name="user_update", methods={ "PUT" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function userUpdate(Request $request, string $uuid): JsonResponse
    {
        $parameters = $this->getContent($request);
        $loggedUser = $this->getLoggedUser();

        // No logged used
        if (null === $loggedUser || ($this->securityService->isSameUser($loggedUser, $uuid) && !$this->securityService->isAdmin($loggedUser))) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $user = $this->userRepository->findOneByUuid($uuid);

        if (null === $user) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'user.notFound', 'L\'utilisateur est introuvable');
        }

        $errors = $this->validate($parameters, [
            'email' => [new Type(['type' => 'string']), new NotBlank()],
            'password' => [new Type(['type' => 'string']), new NotBlank()],
            'birthDate' => [new DateTime(['format' => 'Y-m-d']), new NotBlank()],
            'userName' => [new Type(['type' => 'string'])],
            'firstName' => [new Optional([new Type(['type' => 'string'])])],
            'lastName' => [new Optional([new Type(['type' => 'string'])])],
            'isVerify' => [new Type(['type' => 'bool'])],
        ]);

        // Validation errors
        if (!empty($errors)) {
            return $errors;
        }

        $birthDate = $this->getDate($request, $parameters['birthDate']);
        $firstname = $parameters['firstName'] ?? $user->getFirstname();
        $lastname = $parameters['lastName'] ?? $user->getLastname();

        $user = $this->userService->update(
            $user,
            $parameters['email'],
            $parameters['username'],
            $birthDate,
            $firstname,
            $lastname,
        );

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $user, $loggedUser, ['groups' => 'user:read']);
    }

    /**
     * @Route("/users/checkEmail", name="check_email", methods={ "POST" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function checkEmailAction(Request $request): JsonResponse
    {
        $parameters = $this->getContent($request);

        $errors = $this->validate($parameters, [
            'email' => [new Type(['type' => 'string']), new NotBlank()],
        ]);

        // Validation errors
        if (!empty($errors)) {
            return $errors;
        }

        $user = $this->userRepository->findOneByEmail($parameters['email']);

        if (null !== $user) {
            return $this->buildErrorResponse(Response::HTTP_CONFLICT, 'email.already.exists', 'L\'email existe déjà', 'email');
        }

        return $this->buildSuccessResponse(Response::HTTP_CREATED, []);
    }

    /**
     * @Route("/users/search", name="search_me", methods={ "GET" })
     * }
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function searchAction(Request $request): JsonResponse
    {
        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (null === $loggedUser || !$this->securityService->isAdmin($loggedUser)) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $users = $this->userRepository->search(
            $request->query->get('q'),
            $request->query->get('status')
        );

        return $this->buildSuccessResponse(Response::HTTP_OK, $users, $loggedUser, ['groups' => 'user:search']);
    }
}
