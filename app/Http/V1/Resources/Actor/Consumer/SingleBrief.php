<?php

namespace App\Http\V1\Resources\Actor\Consumer;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleBrief extends JsonResource
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
            'email' => $this->email,
            'createdAt' => $this->created_at,
        ];
    }
}
