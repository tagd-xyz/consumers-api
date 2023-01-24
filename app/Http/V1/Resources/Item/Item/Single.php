<?php

namespace App\Http\V1\Resources\Item\Item;

use App\Http\V1\Resources\Item\Tagd\Single as TagdSingle;
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
            'type' => $this->type,
            'description' => $this->description,
            'properties' => $this->properties,
            'createdAt' => $this->created_at,
            'currentTagd' => $this->when(
                $this->whenLoaded('tagds'),
                new TagdSingle($this->current_tagd)
            ),
            // 'rootTagd' => $this->root_tagd,
        ];
    }
}
