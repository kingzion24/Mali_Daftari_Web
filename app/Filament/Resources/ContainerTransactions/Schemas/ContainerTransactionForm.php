<?php

namespace App\Filament\Resources\ContainerTransactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContainerTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                DatePicker::make('txn_date')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('deposit_delta')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('notes'),
            ]);
    }
}
