<?php

namespace App\Http\V1\Resources\Item\Auction;

use Illuminate\Http\Resources\Json\JsonResource;

class Single extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'createdAt' => $this->created_at,
            'status' => $this->status,
        ];
    }
}
