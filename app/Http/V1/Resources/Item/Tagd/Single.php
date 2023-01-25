<?php

namespace App\Http\V1\Resources\Item\Tagd;

use App\Http\V1\Resources\Actor\Consumer\SingleBrief as ConsumerSingle;
use App\Http\V1\Resources\Item\Item\Single as ItemSingle;
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
            'slug' => $this->slug,
            // TODO: 'consumer' => new ConsumerSingle($this->whenLoaded('consumer')),
            'consumer' => new ConsumerSingle($this->consumer),
            'item' => new ItemSingle($this->whenLoaded('item')),
            'meta' => $this->meta,
            'createdAt' => $this->created_at,
            'activatedAt' => $this->activated_at,
        ];
    }
}
