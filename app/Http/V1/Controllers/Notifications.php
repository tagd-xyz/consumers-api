<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Resources\Notification\Collection as NotificationCollection;
use Illuminate\Http\Request;

class Notifications extends Controller
{
    /**
     * Get basic status info
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $actingAs = $this->actingAs($request);

        // $this->authorize(
        //     'index', [NotificationModel::class, $actingAs]
        // );

        // \Log::info(get_class($actingAs));
        // \Log::info($actingAs);

        $notifications = $actingAs->notifications;

        return response()->withData(
            new NotificationCollection($notifications)
        );
    }
}
