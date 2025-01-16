<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
{
    use ApiResponse;

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
        $id = $this->route('id');

        return [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|min:6|max:8|alpha_num',
            'roles'    => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'      => 'Anda belum mengisi nama',
            'email.required'     => 'Anda belum mengisi email',
            'email.email'        => 'Mohon isikan email yang benar',
            'email.unique'       => 'Email sudah terdaftar',
            'password.min'       => 'Password minimal :min karakter',
            'password.max'       => 'Password maksimal :max karakter',
            'password.alpha_num' => 'Kombinasi password hanya huruf dan atau angka saja',
            'roles.required'     => 'Role wajib diisi',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorValidateResponse($validator->errors()));
    }
}
