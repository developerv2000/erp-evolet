<?php

namespace App\Http\Requests;

use App\Models\ProductSearch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductSearchStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dosage' => [
                Rule::unique(ProductSearch::class)->where(function ($query) {
                    $query->where('inn_id', $this->inn_id)
                    ->where('form_id', $this->form_id)
                    ->where('country_id', $this->country_id)
                    ->where('marketing_authorization_holder_id', $this->marketing_authorization_holder_id)
                    ->where('dosage', $this->dosage)
                    ->where('pack', $this->pack);
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'dosage.unique' => trans('validation.custom.kvpp.unique'),
        ];
    }
}
