<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle Spatie UnauthorizedException or general AuthorizationException
        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof UnauthorizedException || $e instanceof AuthorizationException) {
                // For API requests, return JSON
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'You do not have permission to access this resource.'
                    ], 403);
                }

                // For web requests, show a friendly access denied page
                return response()->view('errors.access_denied', [], 403);
            }
        });
    }
}
