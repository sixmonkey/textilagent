<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class UpdateCurrencyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    #[ArrayShape(['code' => "array"])] public function rules(): array
    {
        return [
            'code' => [
                Rule::unique('currencies', 'code')->ignore($this->currency),
                'string',
                'max:3',
                'min:3'
            ]
        ];
    }
}
