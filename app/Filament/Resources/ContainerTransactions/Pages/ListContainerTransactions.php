<?php

namespace App\Filament\Resources\ContainerTransactions\Pages;

use App\Filament\Resources\ContainerTransactions\ContainerTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContainerTransactions extends ListRecords
{
    protected static string $resource = ContainerTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
