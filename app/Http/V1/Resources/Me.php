<?php

namespace App\Http\V1\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Tagd\Core\Models\User\Role;

class Me extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function toArray($request): array
    {
        $actors = $this
            ->actors()
            ->map(function ($actor) {
                return [
                    'type' => strtolower(basename(str_replace('\\', '/', get_class($actor)))),
                    'id' => $actor->id,
                    'name' => $actor->name,
                ];
            })
            ->filter(function ($actor) {
                return $actor['type'] == Role::CONSUMER;
            })
            ->toArray();

        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'actors' => $actors,
        ];
    }
}
