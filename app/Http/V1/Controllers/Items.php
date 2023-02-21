<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Item\Index as IndexRequest;
use App\Http\V1\Requests\Item\Store as StoreRequest;
use App\Http\V1\Resources\Item\Item\Collection as ItemCollection;
use App\Http\V1\Resources\Item\Item\Single as ItemSingle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Item\Item;
use Tagd\Core\Repositories\Interfaces\Items\Items as ItemsRepo;

class Items extends Controller
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
        $actingAs = $this->actingAs($request);

        $this->authorize(
            'index', [Item::class, $actingAs]
        );

        $items = $itemsRepo->allPaginated([
            'perPage' => $request->get(IndexRequest::PER_PAGE, 25),
            'page' => $request->get(IndexRequest::PAGE, 1),
            'orderBy' => 'created_at',
            'direction' => $request->get(IndexRequest::DIRECTION, 'asc'),
            'relations' => [
                'retailer',
                'tagds',
                'tagds.consumer',
            ],
            'filterFunc' => function ($query) use ($actingAs) {
                switch(get_class($actingAs)) {
                    case Retailer::class:
                        $query->where('retailer_id', $actingAs->id);
                        break;
                    case Reseller::class:
                        $query->whereHas('tagds', function (Builder $builder) use ($actingAs) {
                            $builder->where('reseller_id', $actingAs->id);
                        });
                        break;
                    case Consumer::class:
                        $query->whereHas('tagds', function (Builder $builder) use ($actingAs) {
                            $builder->where('consumer_id', $actingAs->id);
                        });
                        break;
                }
            },
        ]);

        return response()->withData(
            new ItemCollection($items)
        );
    }

    public function store(
        ItemsRepo $itemsRepo,
        StoreRequest $request
    ) {
        $actingAs = $this->actingAs($request);

        $this->authorize(
            'store', [Item::class, $actingAs]
        );

        $item = $itemsRepo
            ->createForConsumer(
                $request->get(StoreRequest::CONSUMER),
                $request->get(StoreRequest::TRANSACTION, ''),
                $actingAs->id, [
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
        Request $request,
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

        $this->authorize(
            'show', [$item, $this->actingAs($request)]
        );

        return response()->withData(
            new ItemSingle($item)
        );
    }
}
