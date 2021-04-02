<?php

namespace App\Exceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $exception
     *
     * @throws \Throwable
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception): Response
    {
        if ($request->expectsJson() || Str::startsWith($request->getRequestUri(), ['/api/',])) {
            return $this->renderApiException($exception);
        }

        return parent::render($request, $exception);
    }

    protected function renderApiException(Throwable $exception): JsonResponse
    {
        $headers = ($exception instanceof HttpExceptionInterface) ? $exception->getHeaders() : [];

        return new JsonResponse(
            $this->convertApiExceptionToArray($exception),
            $this->getApiErrorCode($exception),
            $headers,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

     protected function convertApiExceptionToArray(Throwable $exception): array
    {
        $code = $this->getApiErrorCode($exception);
        $errors = ($exception instanceof  ValidationException) ? $exception->errors() : [];
        return [
            'code'    => $code,
            'message' => $exception->getMessage(),
            'errors'  => $errors,
        ];
    }

    protected function getApiErrorCode(Throwable $exception): int
    {
        if ($exception instanceof ValidationException) {
            return 422;
        }

        if ($exception instanceof ModelNotFoundException) {
            return 404;
        }

        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        if ($exception instanceof AuthenticationException) {
            return 401;
        }

        if ($exception instanceof AuthorizationException) {
            return 403;
        }

        return 500;
    }

}