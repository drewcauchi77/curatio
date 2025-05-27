<?php

namespace App\Traits;

trait HasModuleRules
{
    protected function moduleRules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status_id'   => ['nullable', 'integer']
            // We do not include company_id here because it should always be set from the authenticated user's company_id, not from user input
        ];
    }
}
