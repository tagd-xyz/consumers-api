<?php

namespace App\Providers;

use App\Support\FirebaseToken;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Repositories\Interfaces\Users\Users;

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
    public function boot(Users $users)
    {
        $this->registerPolicies();

        Auth::viaRequest('firebase', function (Request $request) use ($users) {
            $projectId = config('services.firebase.project_id');
            $tenantId = config('services.firebase.tenant_id');

            $token = $request->bearerToken();

            if ($token) {
                $payload = (new FirebaseToken($token))->verify($projectId);

                if ($tenantId == $payload->firebase->tenant) {
                    $user = $users->createFromFirebaseToken($payload);
                    $users->assertIsActingAs($user, Consumer::class);

                    return $user;
                }
            }

            return null;
        });
    }
}
