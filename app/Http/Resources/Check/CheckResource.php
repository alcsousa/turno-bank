<?php

namespace App\Http\Resources\Check;

use App\Http\Resources\Account\AccountResource;
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
            'image_url' => $this->image_url,
            'created_at' => $this->created_at->toDateTimeString(),
            'status' => new CheckStatusResource($this->status),
            'account' => new AccountResource($this->account)
        ];
    }
}
