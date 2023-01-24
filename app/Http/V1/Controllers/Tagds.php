<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Tagd\Index as IndexRequest;
use App\Http\V1\Requests\Tagd\Update as UpdateRequest;
use App\Http\V1\Resources\Item\Tagd\Collection as TagdsCollection;
use App\Http\V1\Resources\Item\Tagd\Single as TagdSingle;
use Illuminate\Routing\Controller as BaseController;
use Tagd\Core\Repositories\Interfaces\Actors\Retailers as RetailersRepo;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsRepo;

class Tagds extends BaseController
{
    /**
     * Get basic status info
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(
        TagdsRepo $tagdsRepo,
        RetailersRepo $retailersRepo,
        IndexRequest $request
    ) {
        $retailerId = $retailersRepo->all()->first()->id;

        $tagds = $tagdsRepo->allPaginated([
            'perPage' => $request->get(IndexRequest::PER_PAGE, 25),
            'page' => $request->get(IndexRequest::PAGE, 1),
            'orderBy' => 'created_at',
            'direction' => $request->get(IndexRequest::DIRECTION, 'asc'),
            'relations' => ['consumer', 'item'],
            // 'filterFunc' => function ($query) use ($retailerId) {
            //     return $query->where('retailer_id', $retailerId);
            // },
        ]);

        return response()->withData(
            new TagdsCollection($tagds)
        );
    }

    public function update(
        TagdsRepo $tagdsRepo,
        UpdateRequest $request,
        string $tagdId
    ) {
        $tagd = $tagdsRepo->findById($tagdId);

        if ($request->has(UpdateRequest::IS_ACTIVE)) {
            if ($request->get(UpdateRequest::IS_ACTIVE)) {
                $tagd->activate();
                $tagd->refresh();
            }
        }

        return response()->withData(
            new TagdSingle($tagd)
        );
    }
}
