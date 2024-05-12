<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Pincode;

class StorePaymentRequest extends FormRequest
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
            'payment_date'  => ['required'],
            // 'amount'        => ['required_if:payment_method,==,Interest','nullable','numeric'],
            'pincode'       => ['required','numeric','digits:4',new Pincode()],
        ];
    }
}
