<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    // public function register(): void
    // {
    //     $this->reportable(function (Throwable $e) {
    //         //
    //     });
    // }
     /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // Intercepter les erreurs d'authentification
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Session non authentifiée ou expirée',
                    'error_code' => 'AUTH_FAILED',
                ], 401);
            }
        });

        // Intercepter les erreurs de capacités manquantes (abilities)
        $this->renderable(function (MissingAbilityException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vous n\'avez pas les permissions requises',
                    'error_code' => 'INSUFFICIENT_PERMISSIONS',
                    'abilities_required' => $e->abilities(),
                ], 403);
            }
        });
    }

    /**
     * Personnalisation de la réponse pour les erreurs d'authentification
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Non autorisé. Veuillez vous connecter.',
                'error_code' => 'AUTH_FAILED',
                'details' => $exception->getMessage(),
            ], 401);
        }

        return redirect()->guest(route('login'));
    }
}
