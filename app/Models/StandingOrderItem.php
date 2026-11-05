<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StandingOrderItem extends Model
{
    protected $guarded = [];

    public function standingOrder(): BelongsTo
    {
        return $this->belongsTo(StandingOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
