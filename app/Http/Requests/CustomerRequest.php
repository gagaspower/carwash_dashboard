<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerRequest extends FormRequest
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
            'customer_name'  => 'required',
            'customer_phone' => 'nullable|numeric|digits_between:9,13'
        ];
    }

    public function messages()
    {
        return [
            'customer_name.required'        => 'Nama wajib diisi',
            'customer_phone.numeric'        => 'Nomor HP hanya angka',
            'customer_phone.digits_between' => 'Nomor HP setidaknya 9 digit dan maksimal 13 digit'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorValidateResponse($validator->errors()));
    }
}
