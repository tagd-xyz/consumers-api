<?php

namespace App\Http\V1\Resources\Resale\AccessRequest;

use App\Http\V1\Resources\Actor\Reseller\Single as ResellerSingle;
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
            'isPending' => $this->is_pending,
            'isApproved' => $this->is_approved,
            'isRevoked' => $this->is_revoked,
            'isRejected' => $this->is_rejected,
            'createdAt' => $this->created_at,
            'approvedAt' => $this->approved_at,
            'rejectedAt' => $this->rejected_at,
            'reseller' => new ResellerSingle($this->whenLoaded('reseller')),
        ];
    }
}
