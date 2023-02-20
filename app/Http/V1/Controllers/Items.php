<?php

namespace App\Http\V1\Controllers;

use App\Http\V1\Requests\Item\Index as IndexRequest;
use App\Http\V1\Requests\Item\Store as StoreRequest;
use App\Http\V1\Resources\Item\Item\Collection as ItemCollection;
use App\Http\V1\Resources\Item\Item\Single as ItemSingle;
use App\Models\Role;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Tagd\Core\Repositories\Interfaces\Items\Items as ItemsRepo;

class Items extends BaseController
{
    private function getActors(string $actingAs): Collection
    {
        switch ($actingAs) {
            case 'retailer':
                return Auth::user()->actorsOfType(Role::RETAILER);
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
        ItemsRepo $itemsRepo,
        IndexRequest $request
    ) {
        $actingAs = $request->header('As-Tagd-Actor', Role::RETAILER);

        $actors = $this->getActors($actingAs);

        $items = $itemsRepo->fetchFor(
            $actingAs,
            $actors, [
                'perPage' => $request->get(IndexRequest::PER_PAGE, 25),
                'page' => $request->get(IndexRequest::PAGE, 1),
                'orderBy' => 'created_at',
                'direction' => $request->get(IndexRequest::DIRECTION, 'asc'),
            ]);

        return response()->withData(
            new ItemCollection($items)
        );
    }

    public function store(
        ItemsRepo $itemsRepo,
        StoreRequest $request
    ) {
        $user = Auth::user();

        // TODO
        $retailerId = $user->actorsOfType(Role::RETAILER)->pluck('id')->first();

        $item = $itemsRepo
            ->createForConsumer(
                $request->get(StoreRequest::CONSUMER),
                $request->get(StoreRequest::TRANSACTION, ''),
                $retailerId, [
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
