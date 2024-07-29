<?php

namespace App\Http\Requests\Check;

use App\Models\CheckStatus;
use Illuminate\Foundation\Http\FormRequest;

class IndexCheckByStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $allowedStatuses = strtolower(implode(',', CheckStatus::$labels));

        return [
            'status' => [
                'required',
                'string',
                'in:' . $allowedStatuses
            ]
        ];
    }
}
