<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Tagd\Index as IndexRequest;
use App\Http\V1\Requests\Tagd\Store as StoreRequest;
use App\Http\V1\Requests\Tagd\Transfer as TransferRequest;
use App\Http\V1\Requests\Tagd\Update as UpdateRequest;
use App\Http\V1\Resources\Item\Tagd\Collection as TagdsCollection;
use App\Http\V1\Resources\Item\Tagd\Single as TagdSingle;
use Illuminate\Routing\Controller as BaseController;
use Tagd\Core\Repositories\Interfaces\Actors\Resellers as ResellersRepo;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsRepo;
use Tagd\Core\Support\Repository\Exceptions\NotFound;

class Tagds extends BaseController
{
    /**
     * Get basic status info
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function index(
        TagdsRepo $tagdsRepo,
        IndexRequest $request
    ) {
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

    public function store(
        TagdsRepo $tagdsRepo,
        ResellersRepo $resellersRepo,
        StoreRequest $request
    ) {
        $reseller = $resellersRepo->findById(
            $request->get(StoreRequest::RESELLER_ID)
        );

        $parentTagdSlug = $request->get(StoreRequest::TAGD_SLUG, null);
        $parentTagd = $tagdsRepo->all([
            'filterFunc' => function ($query) use ($parentTagdSlug) {
                return $query->where('slug', $parentTagdSlug);
            },
        ])->first();

        if (is_null($parentTagd)) {
            throw new NotFound(new \Exception('Tag not found'));
        }

        $tagd = $tagdsRepo->create([
            'parent_id' => $parentTagd->id,
            'item_id' => $parentTagd->item_id,
            'reseller_id' => $reseller->id,
        ]);
        $tagd->activate();
        $tagd->refresh();

        return response()->withData(
            new TagdSingle($tagd)
        );
    }

    public function expire(
        TagdsRepo $tagdsRepo,
        string $tagdId
    ) {
        $tagd = $tagdsRepo->findById($tagdId);

        $tagd->expire();
        $tagd->refresh();

        return response()->withData(
            new TagdSingle($tagd)
        );
    }

    public function transfer(
        TagdsRepo $tagdsRepo,
        TransferRequest $request,
        string $tagdId
    ) {
        $tagd = $tagdsRepo->findById($tagdId);

        $consumerId = $request->get(TransferRequest::CONSUMER_ID);

        // set tagd as transferred
        // $tagd->update([
        //     'consumer_id' => $consumerId,
        // ]);
        $tagd->transfer();
        $tagd->refresh();

        // set tagd siblings as expired
        $siblings = $tagdsRepo->all([
            'filterFunc' => function ($query) use ($tagd) {
                return $query->where('parent_id', $tagd->parent_id)
                ->where('id', '<>', $tagd->id);
            },
        ]);
        foreach ($siblings as $sibling) {
            $sibling->expire();
        }

        // create new tagd
        $tagdNew = $tagdsRepo->create([
            'parent_id' => $tagd->id,
            'item_id' => $tagd->item_id,
            'consumer_id' => $consumerId,
        ]);
        $tagdNew->activate();
        $tagdNew->refresh();

        return response()->withData(
            new TagdSingle($tagdNew)
        );
    }
}
