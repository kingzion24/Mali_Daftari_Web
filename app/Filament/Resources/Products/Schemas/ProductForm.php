<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('size_label')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('unit_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Toggle::make('is_returnable')
                    ->required(),
                TextInput::make('deposit_amount')
                    ->numeric(),
                TextInput::make('reorder_level')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
