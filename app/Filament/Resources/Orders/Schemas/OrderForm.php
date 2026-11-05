<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_no')
                    ->label('Order number')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Generated automatically on save'),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('route_id')
                    ->relationship('route', 'name'),
                Select::make('truck_id')
                    ->relationship('truck', 'name'),
                DatePicker::make('order_date')
                    ->default(now())
                    ->required(),
                DatePicker::make('delivery_date'),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'loaded' => 'Loaded',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),
                Toggle::make('is_standing')
                    ->label('From a standing order'),
                TextInput::make('total')
                    ->label('Total (TZS)')
                    ->numeric()
                    ->prefix('TZS')
                    ->default(0),
            ]);
    }
}
