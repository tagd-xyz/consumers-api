<?php

namespace App\Http\V1\Controllers;

use Illuminate\Routing\Controller as BaseController;

class StatusController extends BaseController
{
    /**
     * Get basic status info
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->withData([
            'now' => \Carbon\Carbon::now(),
        ]);
    }
}
