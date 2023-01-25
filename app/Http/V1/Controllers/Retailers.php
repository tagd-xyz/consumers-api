<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Retailer\Index as IndexRequest;
use App\Http\V1\Resources\Actor\Retailer\Collection as RetailerCollection;
use Illuminate\Routing\Controller as BaseController;
use Tagd\Core\Repositories\Interfaces\Actors\Retailers as RetailersRepo;

class Retailers extends BaseController
{
    /**
     * Get basic status info
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(
        RetailersRepo $retailersRepo,
        IndexRequest $request
    ) {
        $retailers = $retailersRepo->allPaginated([
            'perPage' => $request->get(IndexRequest::PER_PAGE, 500),
            'page' => $request->get(IndexRequest::PAGE, 1),
            'orderBy' => $request->get(IndexRequest::ORDER_BY, 'created_at'),
            'direction' => $request->get(IndexRequest::DIRECTION, 'desc'),
        ]);

        return response()->withData(
            new RetailerCollection($retailers)
        );
    }
}
