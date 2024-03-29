<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\RequestService;
use App\Services\ResponseService;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        protected ResponseService $responseService,
        protected RequestService $requestService
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
     * @throws Exception
     */
    protected function buildErrorResponse(
        int $status,
        string $code,
        ?string $message = null,
        ?string $field = null,
        ?array $data = null
    ): JsonResponse {
        return $this->responseService->error($status, $code, $message, $field, $data);
    }
    // ------------------------------->

    /**
     * @throws ExceptionInterface
     */
    private function normalizeData(mixed $data, ?User $loggedUser = null, ?array $normalizationContext = null): array
    {
        $normalizationContext = $normalizationContext ?? [];
        $data = $data ?? [];

        $normalizationContext = array_merge($normalizationContext, [
            '' => $loggedUser,
        ]);

        return $this->serializer->normalize($data, 'json', $normalizationContext);
    }

    /**
     * Get a datetime and set the timezone if one was
     * given in the request.
     *
     * @throws Exception
     */
    public function getDate(Request $request, ?string $isoTime): ?DateTime
    {
        if (empty($isoTime)) {
            return null;
        }

        $timezone = $this->requestService->getTimezone($request);

        return (new DateTime($isoTime))
            ->setTimezone($timezone);
    }
}
