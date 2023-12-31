<?php

namespace App\Http\V1\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\User\Role;
use Tagd\Core\Models\User\User;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * Returns the first actor of the authenticated user
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
     */
    protected function authUser(): User
    {
        return Auth::user();
    }
}
