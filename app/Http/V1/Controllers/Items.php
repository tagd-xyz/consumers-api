<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Item\Index as IndexRequest;
use App\Http\V1\Requests\Item\Store as StoreRequest;
use App\Http\V1\Resources\Item\Item\Collection as ItemCollection;
use App\Http\V1\Resources\Item\Item\Single as ItemSingle;
use Illuminate\Routing\Controller as BaseController;
use Tagd\Core\Repositories\Interfaces\Actors\Consumers as ConsumersRepo;
use Tagd\Core\Repositories\Interfaces\Items\Items as ItemsRepo;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsRepo;

class Items extends BaseController
{
    /**
     * Get basic status info
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(
        ItemsRepo $itemsRepo,
        IndexRequest $request
    ) {
        $retailerId = $request->get(IndexRequest::RETAILER, null);

        $items = $itemsRepo->allPaginated([
            'perPage' => $request->get(IndexRequest::PER_PAGE, 25),
            'page' => $request->get(IndexRequest::PAGE, 1),
            'orderBy' => 'created_at',
            'direction' => $request->get(IndexRequest::DIRECTION, 'asc'),
            'relations' => [
                'tagds',
                'tagds.consumer',
            ],
            'filterFunc' => function ($query) use ($retailerId) {
                return is_null($retailerId)
                    ? $query
                    : $query->where('retailer_id', $retailerId);
            },
        ]);

        // foreach ($items as $item) {
        //     foreach ($item->tagds as $tagd) {
        //         dd ($tagd->toArray());
        //     }
        // }

        return response()->withData(
            new ItemCollection($items)
        );
    }

    public function store(
        ItemsRepo $itemsRepo,
        ConsumersRepo $consumersRepo,
        TagdsRepo $tagdsRepo,
        StoreRequest $request
    ) {
        $retailerId = $request->get(StoreRequest::RETAILER, null);

        $item = $itemsRepo->create([
            'retailer_id' => $retailerId,
            'name' => $request->get(StoreRequest::NAME, 'Unknown'),
            'description' => $request->get(StoreRequest::DESCRIPTION, 'Unknown'),
            'type' => $request->get(StoreRequest::TYPE, 'Unknown'),
            'properties' => $request->get(StoreRequest::PROPERTIES, []),
        ]);

        $consumer = $consumersRepo->create([
            'name' => $request->get(StoreRequest::CONSUMER, ''),
        ]);

        $tagdsRepo->create([
            'item_id' => $item->id,
            'consumer_id' => $consumer->id,
            'meta' => [
                'transaction' => $request->get(StoreRequest::TRANSACTION, ''),
            ],
        ]);

        return response()->withData(
            new ItemSingle($item)
        );
    }

    public function show(
        ItemsRepo $itemsRepo,
        string $itemId
    ) {
        $item = $itemsRepo->findById($itemId, [
            'relations' => [
                'tagds',
                'tagds.consumer',
                'tagds.reseller',
            ],
        ]);

        return response()->withData(
            new ItemSingle($item)
        );
    }
}
