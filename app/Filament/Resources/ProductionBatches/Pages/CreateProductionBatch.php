<?php

namespace App\Filament\Resources\ProductionBatches\Pages;

use App\Filament\Resources\ProductionBatches\ProductionBatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductionBatch extends CreateRecord
{
    protected static string $resource = ProductionBatchResource::class;
}
