<?php

namespace App\Models;

use App\Models\Traits\CreatedFromFirebaseToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tagd\Core\Models\Actor\Consumer;
use Tagd\Core\Models\Actor\Reseller;
use Tagd\Core\Models\Actor\Retailer;
use Tagd\Core\Models\Traits\HasUuidKey;

class User extends Authenticatable
{
    use CreatedFromFirebaseToken, HasUuidKey, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firebase_id',
        'name',
        'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::autoUuidKey();
    }

    /**
     * Get the roles for this user
     */
    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    /**
     * Get the actors for this user
     *
     * @return Collection
     */
    public function actors(): Collection
    {
        return $this
            ->roles()
            ->with('actor')
            ->map(function ($role) {
                return $role->actor;
            });
    }

    /**
     * Get the actors for this user
     *
     * @param  string  $type
     * @return Collection
     */
    public function actorsOfType(string $type): Collection
    {
        return $this
            ->roles()
            ->with('actor')
            ->get()
            ->filter(function ($v, $k) use ($type) {
                return $v->actor_type == $type;
            })->map(function ($role) {
                return $role->actor;
            });
    }

    /**
     * checks whether or not can act as the given actor
     *
     * @param  Retailer|Reseller|Consumer  $actor
     * @return bool
     */
    public function canActAs(Retailer|Reseller|Consumer $actor): bool
    {
        return $this->roles()->get()->contains(function ($v, $k) use ($actor) {
            return $v->actor_id == $actor->id;
        });
    }

    /**
     * checks whether or not can act as the given actor type
     *
     * @param  string  $type
     * @return bool
     */
    public function canActAsTypeOf(string $type): bool
    {
        return $this->roles()->get()->contains(function ($v, $k) use ($type) {
            return $v->actor_type == $type;
        });
    }

    /**
     * start acting as the given actor
     *
     * @param  Retailer|Reseller|Consumer  $actor
     * @return static
     */
    public function startActingAs(Retailer|Reseller|Consumer $actor): static
    {
        if (! $this->canActAs($actor)) {
            $role = new Role();
            $role->actor()->associate($actor);
            $this->roles()->save($role);
        }

        return $this;
    }

    public function stopActingAs(Retailer|Reseller|Consumer $actor): static
    {
        $this->roles()->whereHas('actor', function (Builder $query) use ($actor) {
            $query->where('id', $actor->id);
        })->delete();

        return $this;
    }
}
