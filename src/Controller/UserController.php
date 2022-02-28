<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\RequestService;
use App\Services\ResponseService;
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
        private UserRepository $userRepository
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
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
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
        if (empty($loggedUser) && in_array('ADMIN', $roles, true)) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
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
        $errors = $this->validate($request->request->all(), [
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

        $birthDate = $this->getDate($request, $request->request->get('birthDate'));

        $user = $this->userService->create(
            $request->request->get('email'),
            $request->request->get('username'),
            $request->request->get('password'),
            $birthDate,
            $request->request->get('firstName'),
            $request->request->get('lastName'),
        );

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $user, $user);
    }

    /**
     * @Route("/users/{uuid}", name="delete_user",methods={"DELETE"})
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function deleteUsers(Request $request, string $uuid)
    {
        $loggedUser = $this->getLoggedUser();
        $roles = $loggedUser->getRoles();

        $user = $this->userRepository->findOneByUuid($uuid);

        // No logged used
        if (empty($loggedUser) || (!in_array('ROLE_ADMIN', $roles, true) && $loggedUser->getUuid() !== $uuid)) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $user, $loggedUser);
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

        // No logged used
        if (empty($loggedUser) || (!in_array('ROLE_ADMIN', $roles, true) && $loggedUser->getUuid() !== $uuid)) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $user = $this->userRepository->findOneBy($uuid);

        if (empty($user)) {
            throw new Exception('L\'utilisateur n\'a pas été trouvé');
        }

        $errors = $this->validate($request->request->all(), [
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

        $birthDate = $this->getDate($request, $request->request->get('birthDate'));

        $user = $this->userService->update(
            $user,
            $request->request->get('email'),
            $request->request->get('username'),
            $birthDate,
            $request->request->get('firstName'),
            $request->request->get('lastName'),
        );

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $user, $loggedUser);
    }
}
