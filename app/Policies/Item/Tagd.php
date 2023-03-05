<?php

namespace App\Policies\Item;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Tagd\Core\Models\Actor\Consumer as ConsumerModel;
use Tagd\Core\Models\Item\Tagd as TagdModel;

class Tagd
{
    use HandlesAuthorization; // HandlesGenericUsers;

    /**
     * Determine whether the user can list.
     *
     * @param  User  $user
     * @param  ConsumerModel  $consumer
     * @return mixed
     */
    public function index(User $user, ConsumerModel $consumer)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can show details.
     *
     * @param  User  $user
     * @param  TagdModel  $tagd
     * @param  ConsumerModel  $consumer
     * @return mixed
     */
    public function show(User $user, TagdModel $tagd, ConsumerModel $consumer)
    {
        // TODO
        return Response::allow();
    }

    /**
     * Determine whether the user can activate.
     *
     * @param  User  $user
     * @param  TagdModel  $tagd
     * @param  ConsumerModel  $consumer
     * @return mixed
     */
    public function activate(User $user, TagdModel $tagd, ConsumerModel $consumer)
    {
        // TODO
        return Response::allow();
    }
}
