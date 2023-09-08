<?php

namespace App\Http\V1\Resources\Actor\Reseller;

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
            'name' => $this->name,
            'logo' => $this->avatar->url ?? null,
            'website' => $this->website,
            'createdAt' => $this->created_at,
        ];
    }
}
