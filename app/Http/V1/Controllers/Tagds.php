<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Tagd\Index as IndexRequest;
use App\Http\V1\Resources\Item\Tagd\Collection as TagdCollection;
use App\Http\V1\Resources\Item\Tagd\Single as TagdSingle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tagd\Core\Models\Item\Tagd;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsRepo;

class Tagds extends Controller
{
    /**
     * Get tagd list
     *
     * @return JsonResponse
     */
    public function index(
        TagdsRepo $tagdsRepo,
        IndexRequest $request
    ) {
        $actingAs = $this->actingAs($request);

        $this->authorize(
            'index', [Tagd::class, $actingAs]
        );

        $tagds = $tagdsRepo->allPaginated([
            'perPage' => $request->get(IndexRequest::PER_PAGE, 25),
            'page' => $request->get(IndexRequest::PAGE, 1),
            'orderBy' => 'created_at',
            'direction' => $request->get(IndexRequest::DIRECTION, 'asc'),
            'relations' => [
                'item',
                'item.retailer',
                'item.images',
                'item.images.upload',
                'consumer',
                'reseller',
            ],
            'filterFunc' => function ($query) use ($actingAs) {
                $query->where('consumer_id', $actingAs->id);
            },
        ]);

        return response()->withData(
            new TagdCollection($tagds)
        );
    }

    /**
     * Shows a tagd
     *
     * @param  Tagd\Core\Repositories\Interfaces\Items\Tagds  $tagdsRepo
     * @return JsonResponse
     */
    public function show(
        Request $request,
        TagdsRepo $tagdsRepo,
        string $tagdId
    ) {
        $tagd = $tagdsRepo->findById($tagdId, [
            'relations' => [
                'item',
                'item.retailer',
                'item.images',
                'item.images.upload',
                'consumer',
                'reseller',
                'auctions',
                'auctions.reseller',
            ],
        ]);

        $this->authorize(
            'show',
            [$tagd, $this->actingAs($request)]
        );

        return response()->withData(
            new TagdSingle($tagd)
        );
    }

    /**
     * Set as available for resale
     *
     * @return JsonResponse
     */
    public function enableForResale(
        Request $request,
        TagdsRepo $tagdsRepo,
        string $tagdId
    ) {
        $tagd = $tagdsRepo->findById($tagdId);

        $this->authorize(
            'enableForResale',
            [$tagd, $this->actingAs($request)]
        );

        $tagd = $tagdsRepo->setAsAvailableForResale($tagd, true);

        return response()->withData(
            new TagdSingle($tagd)
        );
    }

    /**
     * Set as not available for resale
     *
     * @return JsonResponse
     */
    public function disableForResale(
        Request $request,
        TagdsRepo $tagdsRepo,
        string $tagdId
    ) {
        $tagd = $tagdsRepo->findById($tagdId);

        $this->authorize(
            'disableForResale',
            [$tagd, $this->actingAs($request)]
        );

        $tagd = $tagdsRepo->setAsAvailableForResale($tagd, false);

        return response()->withData(
            new TagdSingle($tagd)
        );
    }
}
