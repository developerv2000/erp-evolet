<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderProductUpdateByDDRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new_layout' => ['required'],
            'date_of_sending_new_layout_to_manufacturer' => ['date', 'nullable'],
            'box_article' => ['string', 'nullable'],
            'layout_approved_date' => ['date', 'nullable'],
            'date_of_receiving_print_proof_from_manufacturer' => ['date', 'nullable'],
        ];
    }
}
