<?php

namespace App\Http\Requests\Module;

use App\Models\Module;
use App\Traits\HasModuleRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Inertia\Inertia;

class StoreModuleRequest extends FormRequest
{
    use HasModuleRules;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Module::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return $this->moduleRules();
    }

    // TODO
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * 
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = Inertia::render('modules/Create', [
            // re-pass whatever props you need in the form component
            'module' => $this->route('module'),
        ])->with([
            'success'   => false,
            'message'   => 'general.page-not-available'
        ])->toResponse($this)
            ->setStatusCode(422);

        throw new HttpResponseException($response);
    }
}
