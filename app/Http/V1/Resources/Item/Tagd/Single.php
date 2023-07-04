<?php

namespace App\Http\V1\Resources\Item\Tagd;

use App\Http\V1\Resources\Actor\Consumer\SingleBrief as ConsumerSingle;
use App\Http\V1\Resources\Actor\Reseller\SingleBrief as ResellerSingle;
use App\Http\V1\Resources\Item\Item\Single as ItemSingle;
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
            'slug' => $this->slug,
            'isRoot' => $this->is_root,
            // TODO: 'consumer' => new ConsumerSingle($this->whenLoaded('consumer')),
            'consumer' => new ConsumerSingle($this->whenLoaded('consumer')),
            'reseller' => new ResellerSingle($this->whenLoaded('reseller')),
            'auctions' => new Collection($this->whenLoaded('auctions')),
            'item' => new ItemSingle($this->whenLoaded('item')),
            'meta' => $this->meta,
            'trustScore' => $this->trust_score_simple,
            'isAvailableForResale' => $this->is_available_for_resale,
            'createdAt' => $this->created_at,
            'status' => $this->status,
            'isActive' => $this->is_active,
            'activatedAt' => $this->activated_at,
            'isExpired' => $this->is_expired,
            'expiredAt' => $this->expired_at,
            'isTransferred' => $this->is_transferred,
            'transferredAt' => $this->transferred_at,
        ];
    }
}
