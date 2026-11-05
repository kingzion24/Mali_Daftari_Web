<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    protected $guarded = [];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function standingOrders(): HasMany
    {
        return $this->hasMany(StandingOrder::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
