<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\User;
use App\Repository\FileRepository;
use App\Repository\UserRepository;
use App\Services\FileService;
use App\Services\RequestService;
use App\Services\ResponseService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileController extends HelloworldController
{
    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        private FileService $fileService,
        private FileRepository $fileRepository,
        private UserRepository $userRepository
    ) {
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    /**
     * @Route("/files", name="create_file", methods={"POST"})
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function addAction(Request $request): Response
    {
        $parameters = $this->getContent($request);
        $loggedUser = $this->getLoggedUser();

        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }

        $errors = $this->validate($parameters, [
            'path' => [new Type(['type' => 'string']), new NotBlank()],
        ]);

        if (!empty($errors)) {
            return $errors;
        }

        $file = $this->fileService->create(
            $parameters['path'],
            $loggedUser
        );

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $file, $loggedUser, ['groups' => ['file:read']]);
    }

    /**
     * @Route("/files/{uuid}", name="get_one_file", methods={ "GET"})
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getAction(Request $request, string $uuid): Response
    {
        $loggedUser = $this->getLoggedUser();

        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }
        $file = $this->fileRepository->getOneByStatus($uuid, File::STATUS_ACTIVE);
        if (null === $file) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'not.found', 'Le fichier n\'a pas été trouvé');
        }

        return $this->buildSuccessResponse(Response::HTTP_OK, $file, $loggedUser, ['groups' => ['file:read']]);
    }

    /**
     * @Route("/files", name="get_all_files", methods={"GET"})
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function getAllAction(Request $request): Response
    {
        $loggedUser = $this->getLoggedUser();
        $files = $this->fileRepository->getAllByStatus(File::STATUS_ACTIVE);

        return $this->buildSuccessResponse(Response::HTTP_OK, $files, $loggedUser, ['groups' => ['file:read']]);
    }

    /**
     * @Route("/files/{uuid}", name="delete_file", methods={"DELETE"})
     *
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function delete(Request $request, string $uuid): Response
    {
        $loggedUser = $this->getLoggedUser();
        if (null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }
        $file = $this->fileRepository->findOneBy(['uuid' => $uuid]);
        if (null === $file) {
            return $this->buildErrorResponse(Response::HTTP_NOT_FOUND, 'not.found', 'Le fichier n\'a pas été trouvé');
        }
        $roles = $loggedUser->getRoles();

        if (!in_array(User::ROLE_ADMIN, $roles, true) && $file->getUserId() !== $loggedUser->getId()) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }
        $fileDeleted = $this->fileService->delete($file, $loggedUser);

        return $this->buildSuccessResponse(Response::HTTP_OK, $fileDeleted, $loggedUser, ['groups' => ['file:read']]);
    }
}
