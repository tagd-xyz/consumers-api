<?php

namespace App\Providers;

use App\Models\User;
use App\Support\FirebaseToken;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tagd\Core\Repositories\Interfaces\Actors\Consumers;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \Tagd\Core\Models\Item\Item::class => \App\Policies\Item\Item::class,
        \Tagd\Core\Models\Item\Tagd::class => \App\Policies\Item\Tagd::class,
        \Tagd\Core\Models\Resale\AccessRequest::class => \App\Policies\Resale\AccessRequest::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(Consumers $consumers)
    {
        $this->registerPolicies();

        Auth::viaRequest('firebase', function (
            Request $request
        ) use ($consumers) {
            $token = $request->bearerToken();

            if ($token) {
                $payload = (new FirebaseToken($token))->verify(
                    config('services.firebase.project_id')
                );

                if (
                    config('services.firebase.tenant_id') == $payload->firebase->tenant
                ) {
                    $user = User::createFromFirebaseToken($payload);

                    $consumer = $consumers->assertExists($user->email);

                    $user->startActingAs($consumer);

                    return $user;
                } else {
                    return null;
                }
            }
        });
    }
}
