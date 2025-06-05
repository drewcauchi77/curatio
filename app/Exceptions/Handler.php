<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Throwable;

class Handler extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    // TODO 
    public function render($request, Throwable $exception)
    {
        // Handle authorization errors
        if ($exception instanceof AuthorizationException) {
            return back()->withInput()->with([
                'type' => 'error',
                'title' => 'error.unauthorized',
                'message' => 'error.access-denied',
            ]);
        }

        // Handle database errors gracefully
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
