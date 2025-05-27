<?php

namespace App\Http\Requests\Module;

use Illuminate\Foundation\Http\FormRequest;

class IndexModuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'q'       => ['nullable', 'string', 'max:255'],
            'order'   => ['nullable', 'in:asc,desc'],
            'orderBy' => ['nullable', 'in:title,created_at,updated_at'],
            'status'  => ['nullable', 'in:all,drafts,published,trash'],
        ];
    }
}
