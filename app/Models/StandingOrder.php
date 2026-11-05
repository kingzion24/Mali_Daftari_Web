<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StandingOrder extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'next_delivery' => 'date',
            'active' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StandingOrderItem::class);
    }
}
