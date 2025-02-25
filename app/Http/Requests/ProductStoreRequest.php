<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inn_id' => [
                Rule::unique(Product::class)->where(function ($query) {
                    $query->where('manufacturer_id', $this->manufacturer_id)
                        ->where('inn_id', $this->inn_id)
                        ->where('form_id', $this->form_id)
                        ->where('dosage', $this->dosage)
                        ->where('pack', $this->pack);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'inn_id.unique' => trans('validation.custom.ivp.unique'),
        ];
    }
}
