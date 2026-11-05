<?php

namespace App\Filament\Resources\StandingOrders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StandingOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('route_id')
                    ->relationship('route', 'name'),
                TextInput::make('frequency')
                    ->required()
                    ->default('weekly'),
                DatePicker::make('next_delivery')
                    ->required(),
                Toggle::make('active')
                    ->required(),
                TextInput::make('notes'),
            ]);
    }
}
