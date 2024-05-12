<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Pincode;

class StoreBorrowRequest extends FormRequest
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
            'unique_id'           => ['required','exists:client,unique_id'],
            'disbursement_date'   => ['required'],
            'amount'              => ['required','numeric'],
            'pincode'             => ['required','numeric','digits:4',new Pincode()],
            'pdf_file'            => ['required','mimes:pdf','max:10240'],
            'valid_id'            => ['required','image','mimes:jpg,png,jpeg','max:10240'],
        ];
    }
}
