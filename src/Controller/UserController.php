<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\SecurityService;
use App\Services\UserService;
use Exception;
use RuntimeException;
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
        $loggedUser = $this->getLoggedUser($this->userRepository);

        // No logged user
        if (null === $loggedUser) {
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
        $loggedUser = $this->getLoggedUser($this->userRepository);

        // No logged user
        if (null === $loggedUser) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $roles = $loggedUser->getRoles();

        // No logged used
        if (null === $loggedUser || in_array(User::ROLE_ADMIN, $roles, true)) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $users = $this->userRepository->findAll();
        $usersNormalizer = $this->normalizer->normalize($users, null, ['groups' => 'user.nested']);

        return $this->buildSuccessResponse(Response::HTTP_OK, $usersNormalizer, $loggedUser);
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

        $usersNormalizer = $this->normalizer->normalize($user, null, ['groups' => 'user.nested']);

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $usersNormalizer);
    }

    /**
     * @Route("/users/{uuid}", name="delete_user", methods={ "DELETE" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function deleteUser(string $uuid): JsonResponse
    {
        $loggedUser = $this->getLoggedUser($this->userRepository);
        $user = $this->userRepository->findOneByUuid($uuid);

        if (null === $user) {
            //Fixme Replace by 404
            throw new RuntimeException('L\'utilisateur est introuvable');
        }

        // No logged used
        if (null === $loggedUser || ($this->securityService->isSameUser($loggedUser, $uuid) && !$this->securityService->isAdmin($loggedUser))) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $userDeleted = $this->userService->delete($user, $loggedUser);
        $usersNormalizer = $this->normalizer->normalize($userDeleted, null, ['groups' => 'user.nested']);

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $usersNormalizer, $loggedUser);
    }

    /**
     * @Route("/users/{{uuid}}", name="user_update", methods={ "PUT" })
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function userUpdate(Request $request, string $uuid): JsonResponse
    {
        $parameters = $this->getContent($request);
        $loggedUser = $this->getLoggedUser($this->userRepository);

        // No logged used
        if (null === $loggedUser || ($this->securityService->isSameUser($loggedUser, $uuid) || !$this->securityService->isAdmin($loggedUser))) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $user = $this->userRepository->findOneBy($uuid);

        if (null === $user) {
            //Fixme Replace by 404
            throw new Exception('L\'utilisateur n\'a pas été trouvé');
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

        $usersNormalizer = $this->normalizer->normalize($user, null, ['groups' => 'user.nested']);

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $usersNormalizer, $loggedUser);
    }
}
