<?php

namespace App\Filament\Resources\StandingOrders\Pages;

use App\Filament\Resources\StandingOrders\StandingOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStandingOrder extends EditRecord
{
    protected static string $resource = StandingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
