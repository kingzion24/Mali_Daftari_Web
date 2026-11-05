<?php

namespace App\Filament\Resources\ContainerTransactions\Pages;

use App\Filament\Resources\ContainerTransactions\ContainerTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContainerTransaction extends EditRecord
{
    protected static string $resource = ContainerTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
