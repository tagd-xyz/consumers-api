<?php

namespace App\Models\Traits;

trait CreatedFromFirebaseToken
{
    public static function createFromFirebaseToken(object $payload): static
    {
        $self = static::firstOrCreate([
            'firebase_id' => $payload->user_id,
            'email' => $payload->email,
        ], [
            'name' => $payload->name ?? $payload->email,
        ]);

        return $self;
    }
}
