<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'description' => $this->description,
            'image_path' => $this->image_path,
            'is_approved' => $this->is_approved,
            'reviewed_at' => $this->reviewed_at
        ];
    }
}
