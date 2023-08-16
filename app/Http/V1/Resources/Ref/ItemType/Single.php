<?php

namespace App\Http\V1\Resources\Ref\ItemType;

use Illuminate\Http\Resources\Json\JsonResource;

class Single extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'children' => $this->when(
                $this->whenLoaded('children'),
                new Collection($this->children)
            ),
        ];
    }
}
