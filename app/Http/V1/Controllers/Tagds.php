<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Tagd\Index as IndexRequest;
use App\Http\V1\Requests\Tagd\Store as StoreRequest;
use App\Http\V1\Requests\Tagd\Transfer as TransferRequest;
use App\Http\V1\Requests\Tagd\Update as UpdateRequest;
use App\Http\V1\Resources\Item\Tagd\Collection as TagdsCollection;
use App\Http\V1\Resources\Item\Tagd\Single as TagdSingle;
use App\Models\Role;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Tagd\Core\Repositories\Interfaces\Items\Tagds as TagdsRepo;

class Tagds extends BaseController
{
    private function getActors(string $actingAs): Collection
    {
        switch ($actingAs) {
            case 'retailer':
                return Auth::user()->actorsOfType(Role::RETAILER);
                break;

            case 'reseller':
                return Auth::user()->actorsOfType(Role::RESELLER);
                break;

            case 'consumer':
                return Auth::user()->actorsOfType(Role::CONSUMER);
                break;

            default:
                return collect([]);
        }
    }

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
        StoreRequest $request
    ) {
        $actingAs = $request->header('As-Tagd-Actor', Role::UNKNOWN);

        // TODO: Move to middleware
        if (Role::UNKNOWN == $actingAs) {
            throw new AuthenticationException('Please specify a As-Tagd-Actor header');
        }

        if (Role::RESELLER != $actingAs) {
            throw new AuthenticationException('Not allowed to store tagds');
        }

        $actors = $this->getActors(Role::RESELLER);
        if ($actors->isEmpty()) {
            throw new AuthenticationException('Not allowed to store tagds');
        }

        $parentTagdSlug = $request->get(StoreRequest::TAGD_SLUG, null);
        $tagd = $tagdsRepo->createForResale(
            $actors,
            $parentTagdSlug
        );

        return response()->withData(
            new TagdSingle($tagd)
        );
    }

    public function expire(
        TagdsRepo $tagdsRepo,
        string $tagdId
    ) {
        $tagd = $tagdsRepo->findById($tagdId);

        if (
            $tagd->isTransferred ||
            $tagd->isExpired ||
            ! $tagd->isActive
        ) {
            throw new AuthenticationException('Action not allowed');
        }

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

        if (
            $tagd->isTransferred ||
            $tagd->isExpired ||
            ! $tagd->isActive
        ) {
            throw new AuthenticationException('Action not allowed');
        }

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

        // set parent tagd as transferred
        if ($tagd->parent_id) {
            $tagd->parent->transfer();
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
