<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExpensesRequest extends FormRequest
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
            'tanggal_pencatatan' => 'required',
            'jenis_pengeluaran'  => 'required',
            'jumlah_pengeluaran' => 'required|numeric|gt:0'
        ];
    }

    public function messages()
    {
        return [
            'tanggal_pencatatan.required' => 'Tanggal pengeluran wajib diisi',
            'jenis_pengeluaran.required'  => 'Jenis pengeluaran wajib diisi',
            'jumlah_pengeluaran.required' => 'Jumlah Pengeluaran wajib diisi',
            'jumlah_pengeluaran.numeric'  => 'Jumlah Pengeluaran hanya berupa angka',
            'jumlah_pengeluaran.gt'       => 'Jumlah Pengeluaran tidak boleh nol (0)',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->errorValidateResponse($validator->errors()));
    }
}
