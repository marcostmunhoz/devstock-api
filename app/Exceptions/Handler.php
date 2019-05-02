<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception) {
        if (is_a($exception, \Tymon\JWTAuth\Exceptions\TokenExpiredException::class)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Token de autenticação expirado.'
            ], $exception->getStatusCode());
        } else if (is_a($exception, \Tymon\JWTAuth\Exceptions\TokenInvalidException::class)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Token de autenticação inválido.'
            ], $exception->getStatusCode());
        } else {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
