<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $guarded = [];

    public function trucks(): HasMany
    {
        return $this->hasMany(Truck::class);
    }
}
