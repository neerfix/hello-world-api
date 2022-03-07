<?php

namespace App\Controller;

use App\Services\RequestService;
use App\Services\ResponseService;
use App\Services\AlbumService;
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

class AlbumController extends HelloworldController
{
    // ------------------------ >

    public function __construct(
        ResponseService $responseService,
        RequestService $requestService,
        ValidatorInterface $validator,
        NormalizerInterface $normalizer,
        private AlbumService $albumService
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
        //TODO move it to AbstractController
        $content = $request->getContent();
        $parameters = json_decode($content, true);

        $loggedUser = $this->getLoggedUser();

        // No logged user
        if (null === $loggedUser) {
            //FIXME remove // when front is ready
            // return $this->responseService->error403('auth.unauthorized', 'Vous n\'êtes pas autorisé à effectué cette action');
        }

        $errors = $this->validate($parameters, [
            'title' => [new Type(['type' => 'string']), new NotBlank()],
            'description' => [new Optional([new Type(['type' => 'string']), new NotBlank()])],
            'travelId' => [new Type(['type' => 'int']), new NotBlank()]
        ]);

        if (!empty($errors)) {
            return $errors;
        }

        $createdAt = array_key_exists('createdAt', $parameters) ? $this->getDate($parameters['createdAt']) : null;
        
        $album = $this->albumService->create(
            $parameters['title'],
            $parameters['description'],
            $parameters['travelId'],
            $createdAt
        );

        return $this->buildSuccessResponse(Response::HTTP_CREATED, $album, $loggedUser);
    }
}
