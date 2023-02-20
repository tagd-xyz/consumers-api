<?php

namespace App\Listeners;

use App\Mail\ConsumerCreated as ConsumerCreatedMail;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Mail;
use Tagd\Core\Events\Actors\Consumer\Created;

class Consumer
{
    public function onCreated(Created $event)
    {
        // a consumer was created, send verification email
        Mail::to($event->consumer->email)
            ->send(new ConsumerCreatedMail($event->consumer));
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            Created::class => 'onCreated',
        ];
    }
}
