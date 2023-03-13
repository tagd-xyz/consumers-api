<?php

namespace App\Http\V1\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;

class Controller extends BaseController
{
    use
        AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * Returns the first actor of the authenticated user
     *
     * @param  Request  $request
     * @return Retailer|Reseller|Consumer
     */
    protected function actingAs(Request $request): Retailer|Reseller|Consumer
    {
        return $this
            ->authUser()
            ->actorsOfType(Role::CONSUMER)
            ->first();
    }

    /**
     * Authenticated user
     *
     * @return User
     */
    protected function authUser(): User
    {
        return Auth::user();
    }
}
