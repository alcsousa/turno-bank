<?php

namespace App\Http\Requests\Check;

use Illuminate\Foundation\Http\FormRequest;

class EvaluateCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_accepted' => [
                'required',
                'boolean'
            ]
        ];
    }
}
