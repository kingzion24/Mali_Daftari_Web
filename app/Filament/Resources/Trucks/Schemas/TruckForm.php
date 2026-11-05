<?php

namespace App\Filament\Resources\Trucks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TruckForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('plate_no')
                    ->required(),
                TextInput::make('name'),
                TextInput::make('capacity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('driver_id')
                    ->relationship('driver', 'name'),
            ]);
    }
}
