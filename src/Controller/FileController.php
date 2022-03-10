<?php

namespace App\Controller;

use App\Repository\FileRepository;
use App\Repository\UserRepository;
use App\Services\FileService;
use App\Services\RequestService;
use App\Services\ResponseService;
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
            $parameters['path']
        );

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $file, $loggedUser);
    }
}
