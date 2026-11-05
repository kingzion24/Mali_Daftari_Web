<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('type')
                    ->required(),
                Select::make('route_id')
                    ->relationship('route', 'name'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('address'),
                TextInput::make('price_tier')
                    ->required()
                    ->default('standard'),
                TextInput::make('credit_limit')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
