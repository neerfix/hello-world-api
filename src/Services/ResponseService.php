<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ResponseService
{
    // ---------------------------- >

    public const DATA_RESPONSE_KEY = 'data';
    public const PAGINATION_RESPONSE_KEY = 'pager';
    public const INFORMATION_RESPONSE_KEY = 'information';
    public const ERRORS_RESPONSE_KEY = 'errors';
    public const SUCCESS_RESPONSE = 'success';
    public const ERROR_RESPONSE = 'error';
    public const STATUS_RESPONSE_KEY = 'status';

    // ---------------------------- >
    /**
     * Return a success response.
     */
    public function success(int $status, ?array $data = null, ?array $pagination = null, ?array $information = null): JsonResponse
    {
        $response = [static::DATA_RESPONSE_KEY => []];

        if (!empty($data)) {
            $response[static::DATA_RESPONSE_KEY] = $data;
        }

        if (!empty($pagination)) {
            $response[static::PAGINATION_RESPONSE_KEY] = $pagination;
        }

        if (!empty($information)) {
            $response[static::INFORMATION_RESPONSE_KEY] = $information;
        }

        return new JsonResponse($response, $status);
    }

    /**
     * Return an error to the client.
     *
     * @throws Exception
     */
    public function error(int $status, string $code, ?string $message = null, ?string $field = null, ?array $data = null): JsonResponse
    {
        return $this->errors($status, [$code, $message, $field], $data);
    }

    /**
     * Return errors to the client.
     */
    private function errors(int $status, array $errors, ?array $data = null): JsonResponse
    {
        $response = [static::ERRORS_RESPONSE_KEY => $errors];

        if (!empty($data)) {
            $response[static::DATA_RESPONSE_KEY] = $data;
        }

        return new JsonResponse($response, $status);
    }

    /**
     * Return errors from a list of constraint violations.
     *
     * @throws Exception
     */
    public function errorsFromConstraints(ConstraintViolationListInterface $constraintViolations): JsonResponse
    {
        $errors = [];

        /** @var ConstraintViolation $constraintViolation */
        foreach ($constraintViolations as $constraintViolation) {
            $errors[] = $this->constraintToError($constraintViolation);
        }

        return $this->errors(Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }

    /**
     * Transform a constraint violation into an
     * error array.
     *
     * @throws Exception
     */
    private function constraintToError(ConstraintViolation $constraintViolation): array
    {
        $code = $constraintViolation->getCode();
        $fieldName = str_replace(['[', ']'], '', $constraintViolation->getPropertyPath());

        return $this->createError($code, $constraintViolation->getMessage(), $fieldName);
    }

    /**
     * Create an error to return.
     */
    public function createError(string $code, ?string $message = null, ?string $field = null): array
    {
        if (empty($message)) {
            $message = $code;
        }

        return [
            'code' => $code,
            'message' => $message,
            'field' => $field,
        ];
    }

    /**
     * @throws Exception
     */
    public function error401(string $code = 'auth.unauthorized', ?string $message = null): JsonResponse
    {
        return $this->error(Response::HTTP_UNAUTHORIZED, $code, $message);
    }
}
