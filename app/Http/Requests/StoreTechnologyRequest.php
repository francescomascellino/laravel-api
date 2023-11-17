<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTechnologyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::id() === 1; // SOLOUSER ID 1 PUO' CREARE
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // IL NOME E' RICHIESTO, HA UNA LUNGHEZZA 3/50 E DEVE ESSERE UNICO ALL'INTERNO DEL CAMPO name NELLA TABELLA technologies
            'name' => 'required|bail|min:3|max:50|unique:technologies,name'
        ];
    }
}
