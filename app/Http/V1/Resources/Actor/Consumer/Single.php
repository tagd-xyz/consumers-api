<?php

namespace App\Http\V1\Resources\Actor\Consumer;

use App\Http\V1\Resources\Item\Tagd\Collection as TagdCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class Single extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'createdAt' => $this->created_at,
            'tagds' => $this->when(
                $this->whenLoaded('tagds'),
                new TagdCollection($this->tagds)
            ),
        ];
    }
}
