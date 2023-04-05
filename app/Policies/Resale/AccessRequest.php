<?php

namespace App\Policies\Resale;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Tagd\Core\Models\Actor\Consumer as ConsumerModel;
use Tagd\Core\Models\Resale\AccessRequest as AccessRequestModel;

class AccessRequest
{
    use HandlesAuthorization; // HandlesGenericUsers;

    /**
     * @param  ConsumerModel  $consumer
     * @param  AccessRequestModel  $accessRequest
     * @return bool
     */
    protected function isOwner(
        ConsumerModel $consumer,
        AccessRequestModel $accessRequest
    ): bool {
        return $accessRequest->consumer_id == $consumer->id;
    }

    /**
     * Determine whether the user can show the access request.
     *
     * @param  User  $user
     * @param  AccessRequestModel  $accessRequest
     * @param  ConsumerModel  $consumer
     * @return mixed
     */
    public function show(
        User $user,
        AccessRequestModel $accessRequest,
        ConsumerModel $consumer
    ) {
        return ($this->isOwner($consumer, $accessRequest))
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can approve the access request.
     *
     * @param  User  $user
     * @param  AccessRequestModel  $accessRequest
     * @param  string  $code
     * @param  ConsumerModel  $consumer
     * @return mixed
     */
    public function approve(
        User $user,
        AccessRequestModel $accessRequest,
        string $code,
        ConsumerModel $consumer
    ) {
        $isCodeValid = $accessRequest->code == $code;

        return ($this->isOwner($consumer, $accessRequest) && $isCodeValid)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can reject the access request.
     *
     * @param  User  $user
     * @param  AccessRequestModel  $accessRequest
     * @param  ConsumerModel  $consumer
     * @return mixed
     */
    public function reject(
        User $user,
        AccessRequestModel $accessRequest,
        ConsumerModel $consumer
    ) {
        return ($this->isOwner($consumer, $accessRequest))
            ? Response::allow()
            : Response::deny();
    }
}
