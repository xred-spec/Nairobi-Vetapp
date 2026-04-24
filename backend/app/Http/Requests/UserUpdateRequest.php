<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'nombre'=>['sometimes','nullable','string','max:50'],
            'telefono' => ['sometimes','nullable', 'string', 'max:20'], 
            'email'    => ['sometimes','nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $this->route('id')],
        ];
    }

}
