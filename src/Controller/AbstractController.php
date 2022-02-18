<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\ResponseService;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    // ------------------------------ >

    public function __construct(
        protected ValidatorInterface $validator,
        protected NormalizerInterface $serializer,
        private ResponseService $responseService
    ) {
    }

    // ------------------------------ >

    /* ***** Validator ***** */

    /**
     * Validate the input parameters and return errors as a JsonResponse
     * if there are.
     *
     * @throws Exception
     */
    protected function validate(array $parameters, array $constraints): ?JsonResponse
    {
        $errors = $this->validator->validate($parameters, new Collection([
            'fields' => $constraints,
            'allowExtraFields' => true,
        ]));

        if ($errors->count() > 0) {
            return $this->responseService->errorsFromConstraints($errors);
        }

        return null;
    }

    /* ***** Responses ***** */

    /**
     * @throws ExceptionInterface
     */
    protected function buildSuccessResponse(
        int $status,
        mixed $data,
        ?User $loggedUser = null,
        ?array $normalizationContext = null,
        ?array $pagination = null,
        ?array $information = null
    ): JsonResponse {
        return $this->responseService->success($status, $this->normalizeData($data, $loggedUser, $normalizationContext), $pagination, $information);
    }

    // ------------------------------ >

    /**
     * @throws ExceptionInterface
     */
    private function normalizeData(mixed $data, ?User $loggedUser = null, ?array $normalizationContext = null): array
    {
        $normalizationContext = $normalizationContext ?? [];

        $normalizationContext = array_merge($normalizationContext, [
            '' => $loggedUser,
        ]);

        return $this->serializer->normalize($data, 'json', $normalizationContext);
    }
}
