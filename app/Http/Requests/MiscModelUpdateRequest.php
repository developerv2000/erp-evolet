<?php

namespace App\Http\Requests;

use App\Support\Helpers\ModelHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MiscModelUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $modelName = $this->route('model');
        $model = ModelHelper::addFullNamespaceToModelBasename($modelName);

        return [
            'name' => [Rule::unique($model)->ignore($this->route('id'))]
        ];
    }
}
