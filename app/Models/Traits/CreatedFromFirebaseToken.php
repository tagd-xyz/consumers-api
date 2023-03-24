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

        if ($self->wasRecentlyCreated === true) {
            $self->email = $payload->email;
            $self->name = $payload->name;
            $self->save();
        }

        return $self;
    }
}
