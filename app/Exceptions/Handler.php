<?php
// PHPSTAN CONFIRMED
namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

/**
 * Custom exception handler for application errors.
 */
class Handler extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return RedirectResponse|void
     */
    // TODO 
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return back()->withInput()->with([
                'type' => 'error',
                'title' => 'error.unauthorized',
                'message' => 'error.access-denied',
            ]);
        }

        if ($exception instanceof QueryException) {
            return back()->withInput()->with([
                'type' => 'error',
                'title' => 'error.error',
                'message' => 'error.database-error',
            ]);
        }

        // return parent::render($request, $exception);
    }
}
