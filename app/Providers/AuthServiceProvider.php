<?php

namespace App\Providers;

use App\Models\User;
use App\Support\FirebaseToken;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

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
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('firebase', function (Request $request) {
            $token = $request->bearerToken();

            $payload = (new FirebaseToken($token))->verify(
                config('services.firebase.project_id')
            );

            $user = User::createFromFirebaseToken($payload);

            return $user;
        });
    }
}
