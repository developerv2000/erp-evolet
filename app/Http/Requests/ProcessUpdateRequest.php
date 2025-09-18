<?php

namespace App\Http\Requests;

use App\Models\Process;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class ProcessUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function ensureResponsiblePersonIsUpdated(): void
    {
        $record = Process::withTrashed()->findOrFail($this->route('record'));

        $record->fill($this->all());

        $triggerFields = [
            'status_id',
            'manufacturer_first_offered_price',
            'our_first_offered_price',
            'manufacturer_followed_offered_price',
            'our_followed_offered_price',
            'trademark_en',
            'trademark_ru',
            'marketing_authorization_holder_id',
            'currency_id',
        ];

        if ($record->isDirty($triggerFields) && $record->isClean('responsible_person_id')) {
            throw ValidationException::withMessages([
                'responsible_person_id' => trans('validation.custom.vps.responsible_person_should_be_changed'),
            ]);
        }
    }
}
