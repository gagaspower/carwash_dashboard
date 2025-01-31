<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class ChangePasswordRequest extends FormRequest
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
        return [
            'password'              => 'nullable|min:6|max:8|alpha_num|confirmed',
            'password_confirmation' => 'nullable|same:password'
        ];
    }

    public function messages()
    {
        return [
            'password.min'               => 'Password minimal :min karakter',
            'password.max'               => 'Password maksimal :max karakter',
            'password.alpha_num'         => 'Kombinasi password hanya huruf dan atau angka saja',
            'password.confirmed'         => 'Password dan konfirmasi password tidak cocok',
            'password_confirmation.same' => 'Konfirmasi password harus sama dengan password'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorValidateResponse($validator->errors()));
    }
}
