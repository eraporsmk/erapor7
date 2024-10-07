<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException ;
use Illuminate\Database\QueryException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        ValidationException::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e) {
            if ($e instanceof AuthenticationException) {
                $data = [
                    'message' => $e->getMessage(),
                ];
                return response()->json($data, 401);
            }
            if($e instanceof QueryException){
                $data = [
                    'icon' => 'error',
                    'title' => 'ERROR 500!',
                    'text' => $e->getMessage(),
                ];
                return response()->json($data, 500);
            }
            if (!$e instanceof ValidationException) {
                if (method_exists('getStatusCode', $e)) {
                    if(!$e->getStatusCode() == 503){
                        $data = [
                            'icon' => 'error',
                            'title' => 'ERROR 500!',
                            'text' => $e->getMessage(),
                        ];
                    } else {
                        $data = [
                            'icon' => 'error',
                            'title' => 'ERROR 500!',
                            'text' => $e->getMessage(),
                        ];
                    }
                } else {
                    $data = [
                        'icon' => 'error',
                        'title' => 'ERROR 500!',
                        'text' => $e->getMessage(),
                    ];
                }
                return response()->json($data, 500);
            }
        });
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
