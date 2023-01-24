<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Item\Index as IndexRequest;
use App\Http\V1\Requests\Item\Store as StoreRequest;
use App\Http\V1\Resources\Item\Item\Collection as ItemCollection;
use App\Http\V1\Resources\Item\Item\Single as ItemSingle;
use Illuminate\Routing\Controller as BaseController;
use Tagd\Core\Repositories\Interfaces\Actors\Retailers as RetailersRepo;
use Tagd\Core\Repositories\Interfaces\Items\Items as ItemsRepo;

class Items extends BaseController
{
    /**
     * Get basic status info
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(
        ItemsRepo $itemsRepo,
        RetailersRepo $retailersRepo,
        IndexRequest $request
    ) {
        $retailerId = $this->findRetailerByName(
            $retailersRepo,
            $request->get(IndexRequest::RETAILER, null)
        );

        $items = $itemsRepo->allPaginated([
            'perPage' => $request->get(IndexRequest::PER_PAGE, 25),
            'page' => $request->get(IndexRequest::PAGE, 1),
            'orderBy' => 'created_at',
            'direction' => $request->get(IndexRequest::DIRECTION, 'asc'),
            'relations' => ['tagds'],
            'filterFunc' => function ($query) use ($retailerId) {
                return is_null($retailerId)
                    ? $query
                    : $query->where('retailer_id', $retailerId);
            },
        ]);

        return response()->withData(
            new ItemCollection($items)
        );
    }

    public function store(
        ItemsRepo $itemsRepo,
        RetailersRepo $retailersRepo,
        StoreRequest $request
    ) {
        $retailerId = $this->findRetailerByName(
            $retailersRepo,
            $request->get(StoreRequest::RETAILER, null)
        );

        $item = $itemsRepo->create([
            'retailer_id' => $retailerId,
            'name' => $request->get(StoreRequest::NAME, 'Unknown'),
            'description' => $request->get(StoreRequest::DESCRIPTION, 'Unknown'),
            'type' => $request->get(StoreRequest::TYPE, 'Unknown'),
            'properties' => $request->get(StoreRequest::PROPERTIES, []),
        ]);

        return response()->withData(
            new ItemSingle($item)
        );
    }

    public function show(
        ItemsRepo $itemsRepo,
        string $itemId
    ) {
        $item = $itemsRepo->findById($itemId);

        return response()->withData(
            new ItemSingle($item)
        );
    }

    private function findRetailerByName(RetailersRepo $repo, string $name = null): ?string
    {
        return is_null($name)
            ? null
            : $repo->all([
                'filterFunc' => function ($query) use ($name) {
                    return $query->where('name', $name);
                },
            ])->first()->id;
    }
}
