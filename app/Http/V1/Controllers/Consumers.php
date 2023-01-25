<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Consumer\Index as IndexRequest;
use App\Http\V1\Resources\Actor\Consumer\Collection as ConsumerCollection;
use App\Http\V1\Resources\Actor\Consumer\Single as ConsumerSingle;
use Illuminate\Routing\Controller as BaseController;
use Tagd\Core\Repositories\Interfaces\Actors\Consumers as ConsumersRepo;

class Consumers extends BaseController
{
    /**
     * Get basic status info
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(
        ConsumersRepo $consumersRepo,
        IndexRequest $request
    ) {
        $consumers = $consumersRepo->allPaginated([
            'perPage' => $request->get(IndexRequest::PER_PAGE, 500),
            'page' => $request->get(IndexRequest::PAGE, 1),
            'orderBy' => $request->get(IndexRequest::ORDER_BY, 'created_at'),
            'direction' => $request->get(IndexRequest::DIRECTION, 'desc'),
            'relations' => ['tagds'],
        ]);

        return response()->withData(
            new ConsumerCollection($consumers)
        );
    }

    public function show(
        ConsumersRepo $consumersRepo,
        string $consumerId
    ) {
        $consumer = $consumersRepo->findById(
            $consumerId,
            [
                'relations' => [
                    'tagds',
                    'tagds.item',
                ],
            ],
        );

        return response()->withData(
            new ConsumerSingle($consumer)
        );
    }
}
