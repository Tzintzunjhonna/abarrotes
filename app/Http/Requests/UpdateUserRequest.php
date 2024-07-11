<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
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
        $userId = request()->route('id');

        return [
            'name'       => 'required',
            'first_name' => 'required',
            'last_name'  => '',
            'phone'      => 'required|min:10|max:10|unique:users,phone,'. $userId,
            'email'      => 'required|email|unique:users,email,' . $userId,
            'password'   => '',
            'role'       => 'required',
        ];
    }

    public function messages()
    {
        return [
           'phone.unique' => 'El número de teléfono ya ha sido registrado.',
           'email.unique' => 'El correo electrónico ya ha sido registrado.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'data'   => $validator->errors()
        ], 422));
    }
}
