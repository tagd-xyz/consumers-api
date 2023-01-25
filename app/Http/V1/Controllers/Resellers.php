<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Reseller\Index as IndexRequest;
use App\Http\V1\Resources\Actor\Reseller\Collection as ResellerCollection;
use App\Http\V1\Resources\Actor\Reseller\Single as ResellerSingle;
use Illuminate\Routing\Controller as BaseController;
use Tagd\Core\Repositories\Interfaces\Actors\Resellers as ResellersRepo;

class Resellers extends BaseController
{
    /**
     * Get basic status info
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(
        ResellersRepo $resellersRepo,
        IndexRequest $request
    ) {
        $resellers = $resellersRepo->allPaginated([
            'perPage' => $request->get(IndexRequest::PER_PAGE, 500),
            'page' => $request->get(IndexRequest::PAGE, 1),
            'orderBy' => $request->get(IndexRequest::ORDER_BY, 'created_at'),
            'direction' => $request->get(IndexRequest::DIRECTION, 'desc'),
        ]);

        return response()->withData(
            new ResellerCollection($resellers)
        );
    }

    public function show(
        ResellersRepo $resellersRepo,
        string $resellerId
    ) {
        $reseller = $resellersRepo->findById(
            $resellerId,
            [
                'relations' => [
                    'tagds',
                    'tagds.item',
                ],
            ],
        );

        return response()->withData(
            new ResellerSingle($reseller)
        );
    }
}
