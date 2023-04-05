<?php

namespace App\Http\V1\Resources\Actor\Reseller;

use App\Http\V1\Resources\Item\Tagd\Collection as TagdCollection;
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
            'name' => $this->name,
            'logo' => $this->logo,
            'website' => $this->website,
            'createdAt' => $this->created_at,
            'tagds' => new TagdCollection($this->whenLoaded('tagds')),
        ];
    }
}
