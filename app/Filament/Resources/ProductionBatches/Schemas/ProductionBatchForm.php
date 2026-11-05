<?php

namespace App\Filament\Resources\ProductionBatches\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductionBatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('location_id')
                    ->relationship('location', 'name'),
                TextInput::make('batch_code')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('produced_on')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('completed'),
            ]);
    }
}
