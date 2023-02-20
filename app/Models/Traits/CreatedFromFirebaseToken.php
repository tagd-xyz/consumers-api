<?php

namespace App\Models\Traits;

trait CreatedFromFirebaseToken
{
    /**
     * @param  object  $payload
     * @return static
     */
    public static function createFromFirebaseToken(object $payload): static
    {
        $self = static::firstOrCreate([
            'firebase_id' => $payload->user_id,
        ]);

        if ($self->email != $payload->email ?? null) {
            $self->email = $payload->email;
        }

        if ($self->name != $payload->name ?? null) {
            $self->name = $payload->name;
        }

        if ($self->wasRecentlyCreated) {
            //TODO assign consumer roles to claim tagds
        }

        $self->save(); // will save only if fields have changed

        return $self;
    }
}
