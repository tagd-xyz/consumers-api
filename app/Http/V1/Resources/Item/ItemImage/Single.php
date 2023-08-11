<?php

namespace App\Http\V1\Resources\Item\ItemImage;

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
            'url' => $this->url,
            'portrait' => $this->portrait_url,
            'thumbnail' => $this->preview_url,
            'square' => $this->square_url,
        ];
    }
}
