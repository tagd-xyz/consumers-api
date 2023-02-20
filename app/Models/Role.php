<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const RETAILER = 'retailer';

    public const RESELLER = 'reseller';

    public const CONSUMER = 'consumer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'actor_type',
        'actor_id',
    ];

    /**
     * Get the user who has this role
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent actor model (retailer, reseller, consumer).
     */
    public function actor()
    {
        return $this->morphTo();
    }
}
