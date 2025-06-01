<?php

namespace App\Http\Requests\Module;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates module listing/index requests.
 */
class IndexModuleRequest extends FormRequest
{
    /**
     * Check if user is authorized to list modules.
     * 
     * @return bool Always true (authorization handled by controller)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get validation rules for filtering and sorting parameters.
     *
     * @return array Validation rules for search, ordering, and status filters
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'in:asc,desc'],
            'orderBy' => ['nullable', 'in:title,created_at,updated_at'],
            'status' => ['nullable', 'in:all,draft,published,deleted'],
        ];
    }
}
