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
        NormalizerInterface $normalizer,
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
        if (empty($loggedUser)) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        return $this->buildSuccessResponse(Response::HTTP_OK, $loggedUser, $loggedUser);
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
        $roles = $loggedUser->getRoles();

        // No logged used
        if (empty($loggedUser) || in_array(User::ROLE_ADMIN, $roles, true)) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $users = $this->userRepository->findAll();

        return $this->buildSuccessResponse(Response::HTTP_OK, $users, $loggedUser);
    }

    /**
     * @Route("/users", name="auth_signup_email", methods={ "POST" })
     *
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function userSignup(Request $request): JsonResponse
    {
        //TODO move it to AbstractController
        $content = $request->getContent();
        $parameters = json_decode($content, true);

        $errors = $this->validate($parameters, [
            'email' => [new Email(), new NotBlank()],
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

        $user = $this->userService->create(
            $parameters['email'],
            $parameters['username'],
            $parameters['password'],
            $birthDate,
            $parameters['firstName'],
            $parameters['lastName'],
        );

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $user);
    }

    /**
     * @Route("/users/{uuid}", name="delete_user", methods={ "DELETE" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function deleteUser(Request $request, string $uuid): JsonResponse
    {
        $loggedUser = $this->getLoggedUser();

        $user = $this->userRepository->findOneByUuid($uuid);

        if (empty($user)) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'user.notFound', 'L\'utilisateur est introuvable');
        }

        // No logged used
        if (empty($loggedUser) || ($this->securityService->isSameUser($loggedUser, $uuid) && !$this->securityService->isAdmin($loggedUser))) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $userDeleted = $this->userService->delete($user, $loggedUser);

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $userDeleted, $loggedUser);
    }

    /**
     * @Route("/users/{{uuid}}", name="user_update", methods={ "PUT" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function userUpdate(Request $request, string $uuid): JsonResponse
    {
        $loggedUser = $this->getLoggedUser();
        $roles = $loggedUser->getRoles();

        //TODO move it to AbstractController
        $content = $request->getContent();
        $parameters = json_decode($content, true);

        // No logged used
        if (empty($loggedUser) || ($this->securityService->isSameUser($loggedUser, $uuid) && !$this->securityService->isAdmin($loggedUser))) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $user = $this->userRepository->findOneBy($uuid);

        if (empty($user)) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'user.notFound', 'L\'utilisateur est introuvable');
        }

        $errors = $this->validate($parameters, [
            'email' => [new Email(), new NotBlank()],
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

        $user = $this->userService->update(
            $user,
            $parameters['email'],
            $parameters['username'],
            $birthDate,
            $parameters['firstName'],
            $parameters['lastName'],
        );

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $user, $loggedUser);
    }
}
