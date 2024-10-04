<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateUserRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'       => 'required',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:8',
            'role'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phone.unique' => 'El número de teléfono ya ha sido registrado.',
            'email.unique' => 'El correo electrónico ya ha sido registrado.',
            'name.required' => 'El nombre es requerido.',
            'password.required' => 'La contraseña es requerido.',
            'password.min' => 'La contraseña deberá se minimo 8 caracteres',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->fail([
            'status' => 'fail',
            'data'   => $validator->errors()
        ]));
    }
}
