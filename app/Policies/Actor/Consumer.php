<?php

namespace App\Policies\Actor;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Tagd\Core\Models\Actor\Actor as ActorModel;
use Tagd\Core\Models\Actor\Consumer as ConsumerModel;
use Tagd\Core\Models\Actor\Reseller as ResellerModel;
use Tagd\Core\Models\Actor\Retailer as RetailerModel;
use Tagd\Core\Models\Item\Item as ItemModel;

class Consumer
{
    use HandlesAuthorization; // HandlesGenericUsers;

    /**
     * Determine whether the user can list.
     *
     * @param  User  $user
     * @param  ActorModel  $actor
     * @return mixed
     */
    public function index(User $user, ActorModel $actor)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can see details.
     *
     * @param  User  $user
     * @param  ItemModel  $item
     * @param  ActorModel  $actor
     * @return mixed
     */
    public function show(User $user, ConsumerModel $item, ActorModel $actor)
    {
        // TODO
        switch (get_class($actor)) {
            case ConsumerModel::class:
                return $item->id == $actor->id
                    ? Response::allow()
                    : Response::deny();

            case RetailerModel::class:
            case ResellerModel::class:
                return Response::deny();
                break;
        }
    }
}
