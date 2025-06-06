<?php

namespace App\Traits\Module;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait HandlesModulePageRedirect
{
    private function redirectToFirstPage(Request $request, string $routeName, array $additionalParams = []): RedirectResponse
    {
        $params = array_merge(
            $request->except('page'),
            $additionalParams
        );

        return redirect()->route($routeName, $params)
            ->with([
                'type' => 'error',
                'title' => 'errors.pagination.not-available.title',
                'message' => 'errors.pagination.not-available.description'
            ]);
    }
}
