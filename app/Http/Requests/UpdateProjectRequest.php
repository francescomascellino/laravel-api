<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return true;
        return Auth::id() === 1; // SOLO USER ID 1 PPUO' AGGIORNARE
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type_id' => 'nullable|exists:types,id', // exists CONTROLLA SE IL CAMPO id E' PRESENTE NELLA TABELLA types
            'title' => ['required', 'bail', 'min:3', 'max:200', Rule::unique('projects')->ignore($this->project)],
            'thumb' => 'nullable|image|max:300',
            'description' => 'nullable|bail|min:3|max:500',
            // 'tech' => 'nullable|bail|min:3|max:200',
            'github' => 'nullable|bail|min:3|max:2048|url:http,https',
            'link' => 'nullable|bail|min:3|max:2048|url:http,https',
        ];
    }
}
