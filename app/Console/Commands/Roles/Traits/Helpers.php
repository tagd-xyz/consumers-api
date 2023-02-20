<?php

namespace App\Console\Commands\Roles\Traits;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;

trait Helpers
{
    /**
     * current user roles
     *
     * @param  User  $user
     * @return Collection
     */
    protected function currentRoles(User $user): Collection
    {
        return $user
            ->roles()
            ->with('actor')
            ->get()
            ->map(function ($role) {
                return [
                    'type' => $role->actor_type,
                    'name' => $role->actor->name,
                ];
            });
    }

    /**
     * show current user roles
     *
     * @param  User  $user
     * @return void
     */
    protected function showCurrentRoles(User $user)
    {
        $this->table(
            ['Type', 'Name'],
            $this->currentRoles($user)->toArray()
        );
    }

    /**
     * prompt for role
     *
     * @return string
     */
    protected function askForRole(): string
    {
        return $this->choice(
            'What is the role?',
            [
                Role::RETAILER,
                Role::RESELLER,
                Role::CONSUMER,
            ]
        );
    }

    /**
     * prompt for actor
     *
     * @param  string  $role
     * @return Retailer|Reseller|Consumer
     */
    protected function askForActor(string $role): Retailer|Reseller|Consumer
    {
        switch($role) {
            case Role::RETAILER:
                $actors = Retailer::all();
                break;
            case Role::RESELLER:
                $actors = Reseller::all();
                break;
            case Role::CONSUMER:
                $actors = Consumer::all();
                break;
        }

        $actorName = $this->choice(
            'Which actor?',
            $actors->map(function ($item) {
                return $item->name;
            })->toArray()
        );

        return $actors->first(function ($actor) use ($actorName) {
            return $actor->name == $actorName;
        });
    }
}
