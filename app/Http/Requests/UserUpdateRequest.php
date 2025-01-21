<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $recordID = $this->route('record')->id;

        return [
            'name' => ['string', 'max:255', Rule::unique(User::class)->ignore($recordID)],
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($recordID)],
            'photo' => ['file', File::types(['png', 'jpg', 'jpeg']), 'nullable'],
            'department_id' => ['required'],
            'roles' => ['required'],
            'permissions' => ['nullable'],
            'responsibleCountries' => ['nullable'],
        ];
    }
}
