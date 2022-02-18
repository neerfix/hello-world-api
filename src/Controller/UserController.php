<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\RequestService;
use App\Services\ResponseService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends HelloworldController
{
    // ------------------------------ >

    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
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
        if (empty($loggedUser) && in_array("ADMIN",$roles)) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $users = $this->userRepository->findAll();

        return $this->buildSuccessResponse(Response::HTTP_OK, $users, $loggedUser);
    }

    /**
     * @Route("/users/signup", name="auth_signup_email", methods={ "POST" })
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws WorkingException
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function userSignup(Request $request): JsonResponse
    {
        $errors = $this->validate($request->request->all(), [
            'email' => [new Email(), new NotBlank()],
            'password' => [new Type(['type' => 'string']), new NotBlank()],
            'birthDate' => [new DateTime(['format' => 'Y-m-d']), new NotBlank()],
            'userName' => [new Type(['type' => 'string'])],
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
            $request->request->get('password'),
            $request->request->get('userName'),
            $birthDate,
            $request->request->get('firstName'),
            $request->request->get('lastName'),
        );

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $user, $user);
    }

    /**
     * @Route("/users/{id}/delete", name="delete_user",methods={"DELETE"})
     */
    public function deleteUsers($id,UserRepository $repo,  EntityManagerInterface $manager){
        $loggedUser = $this->getLoggedUser();
        $roles = $loggedUser->getRoles();
        // No logged used
        if (empty($loggedUser) || in_array("ADMIN",$roles)) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $exists = 'no';
        if($user = $repo->find($id)) {
            $exist = 'yes';
            $manager->remove($user);
            $manager->flush();
        }
        $response = new Response('{ "id" : '. $id .' '. $exist.'  }');
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * @Route("/users/update", name="user_update, methods={ "POST" })
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws WorkingException
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function userUpdate(Request $request,UserRepository $repo,  EntityManagerInterface $manager): JsonResponse
    {
        $loggedUser = $this->getLoggedUser();

        $roles = $loggedUser->getRoles();

        // No logged used
        if (empty($loggedUser) || in_array("ADMIN",$roles)) {
            return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $errors = $this->validate($request->request->all(), [
            'id' => [new Uuid()],
            'email' => [new Email(), new NotBlank()],
            'password' => [new Type(['type' => 'string']), new NotBlank()],
            'birthDate' => [new DateTime(['format' => 'Y-m-d']), new NotBlank()],
            'userName' => [new Type(['type' => 'string'])],
            'firstName' => [new Optional([new Type(['type' => 'string'])])],
            'lastName' => [new Optional([new Type(['type' => 'string'])])],
            'isVerify' => [new Type(['type' => 'bool'])]
        ]);

        // Validation errors
        if (!empty($errors)) {
            return $errors;
        }

        $exists = 'no';

        $id = $request->request->get('id');

        $birthDate = $this->getDate($request, $request->request->get('birthDate'));

        $user = $repo->find($id);
        if($user) {
            $exist = 'yes';
            $user->setEmail($request->request->get('email'));
            $user->setPassword($request->request->get('password'));
            $user->setUsername($request->request->get('userName'));
            $user->setDateOfBirth($birthDate);
            $user->setFirstname($request->request->get('firstName'));
            $user->setLastname($request->request->get('lastName'));
            $user->setIsVerify($request->request->get('isVerify'));
        }

        $response = new Response('{ "id" : '. $id .' '. $exist.'  }');
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $this->buildSuccessResponse(Response::HTTP_ACCEPTED, $user, $user);
    }

}
