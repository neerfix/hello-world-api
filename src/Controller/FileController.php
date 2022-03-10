<?php

namespace App\Controller;

use App\Repository\FileRepository;
use App\Services\FileService;
use App\Services\RequestService;
use Symfony\Component\HttpFoundation\Request;
use App\Services\ResponseService;
use Ramsey\Uuid\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FileController extends HelloworldController
{
    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        private NormalizerInterface $normalizer,
        private FileService $fileService,
        private FileRepository $fileRepository
    ){
        parent::__construct($responseService, $requestService, $validator, $normalizer);
    }

    /**
     * @Route("/files", name="create_file", methods={"POST})
     * @throws ExceptionInterface
     * @throws Exception
     */
    public function addAction(Request $request): Response
    {
        $parameters = $this->getContent($request);
        $loggedUser = $this->getLoggedUser();

        if(null === $loggedUser) {
            return $this->buildErrorResponse(Response::HTTP_FORBIDDEN, 'auth.unauthorized', 'Vous n\'êtes pas autorisé à effectuer cette action');
        }
        return $this->buildSuccessResponse(Response::HTTP_CREATED,"truc", $loggedUser);
    }
}