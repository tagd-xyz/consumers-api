<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Item\Index as IndexRequest;
use App\Http\V1\Resources\Item\Item\Collection as ItemCollection;
use Illuminate\Database\Eloquent\Builder;
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
                $query->whereHas('tagds', function (Builder $builder) use ($actingAs) {
                    $builder->where('consumer_id', $actingAs->id);
                });
            },
        ]);

        return response()->withData(
            new ItemCollection($items)
        );
    }
}
