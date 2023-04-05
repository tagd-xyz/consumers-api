<?php

namespace App\Policies\Item;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Tagd\Core\Models\Actor\Consumer as ConsumerModel;
use Tagd\Core\Models\Item\Item as ItemModel;

class Item
{
    use HandlesAuthorization; // HandlesGenericUsers;

    /**
     * Determine whether the user can list.
     *
     * @return mixed
     */
    public function index(User $user, ConsumerModel $consumer)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can show details.
     *
     * @return mixed
     */
    public function show(User $user, ItemModel $item, ConsumerModel $consumer)
    {
        // TODO
        return Response::allow();
    }
}
