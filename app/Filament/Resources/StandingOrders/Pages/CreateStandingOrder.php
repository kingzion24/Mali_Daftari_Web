<?php

namespace App\Filament\Resources\StandingOrders\Pages;

use App\Filament\Resources\StandingOrders\StandingOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStandingOrder extends CreateRecord
{
    protected static string $resource = StandingOrderResource::class;
}
