<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\AccessRequest\Approve as ApproveRequest;
use App\Http\V1\Resources\Resale\AccessRequest\Single as AccessRequestSingle;
use Illuminate\Http\Request;
use Tagd\Core\Repositories\Interfaces\Resales\AccessRequests as AccessRequestsRepo;

class ResaleAccessRequests extends Controller
{
    /**
     * Shows an access request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function show(
        AccessRequestsRepo $accessRequestsRepo,
        Request $request,
        int $accessRequestId
    ) {
        $accessRequest = $accessRequestsRepo->findById($accessRequestId);

        $this->authorize(
            'show',
            [$accessRequest, $this->actingAs($request)]
        );

        return response()->withData(
            new AccessRequestSingle($accessRequest)
        );
    }

    /**
     * Rejects an access request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function reject(
        AccessRequestsRepo $accessRequestsRepo,
        Request $request,
        int $accessRequestId
    ) {
        $accessRequest = $accessRequestsRepo->findById($accessRequestId);

        $this->authorize(
            'reject',
            [$accessRequest, $this->actingAs($request)]
        );

        $accessRequest = $accessRequestsRepo->reject($accessRequest);

        return response()->withData(
            new AccessRequestSingle($accessRequest)
        );
    }

    /**
     * Approves an access request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function approve(
        AccessRequestsRepo $accessRequestsRepo,
        ApproveRequest $request,
        int $accessRequestId
    ) {
        $accessRequest = $accessRequestsRepo->findById($accessRequestId);

        $this->authorize(
            'approve',
            [
                $accessRequest,
                $request->get(ApproveRequest::CODE),
                $this->actingAs($request),
            ]
        );

        $accessRequest = $accessRequestsRepo->approve($accessRequest);

        return response()->withData(
            new AccessRequestSingle($accessRequest)
        );
    }
}
