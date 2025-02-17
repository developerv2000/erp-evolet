<?php

namespace App\Http\Requests;

use App\Models\Meeting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MeetingStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'year' => [
                Rule::unique(Meeting::class)->where(function ($query) {
                    $query->where('year', $this->year)
                        ->where('manufacturer_id', $this->manufacturer_id);
                })
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'year.unique' => trans('validation.custom.meetings.unique'),
        ];
    }
}
