<?php

namespace App\Filament\Resources\StandingOrders\Pages;

use App\Filament\Resources\StandingOrders\StandingOrderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStandingOrders extends ListRecords
{
    protected static string $resource = StandingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
