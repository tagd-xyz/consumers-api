<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Notification\Update as UpdateRequest;
use App\Http\V1\Resources\Notification\Collection as NotificationCollection;
use App\Http\V1\Resources\Notification\Single as NotificationSingle;
use Illuminate\Http\Request;

class Notifications extends Controller
{
    /**
     * List of notifications
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $actingAs = $this->actingAs($request);

        // $this->authorize(
        //     'index', [NotificationModel::class, $actingAs]
        // );

        $notifications = $actingAs->notifications;

        return response()->withData(
            new NotificationCollection($notifications)
        );
    }

    public function update(UpdateRequest $request, $notificationId)
    {
        $actingAs = $this->actingAs($request);

        if ($request->has(UpdateRequest::IS_READ)) {
            if ($request->get(UpdateRequest::IS_READ, false)) {
                $notification = $actingAs
                    ->notifications()
                    ->where('id', $notificationId)
                    ->firstOrFail();

                $notification->markAsRead();

                return response()->withData(
                    new NotificationSingle($notification)
                );
            }
        }

        return response()->withData([], 204);
    }
}
