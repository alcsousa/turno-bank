<?php

namespace App\Http\Requests\Check;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class StoreCheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['required', 'string', 'min:1', 'max:255'],
            'image' => [
                'required',
                'mimes:jpg,png',
                File::image()
                    ->max(2048)
                    ->dimensions(Rule::dimensions()
                        ->maxWidth(1920)
                        ->maxHeight(1080))
            ],
        ];
    }
}
