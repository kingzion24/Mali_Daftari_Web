<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('contact_name'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('source')
                    ->required()
                    ->default('referral'),
                TextInput::make('stage')
                    ->required()
                    ->default('new'),
                TextInput::make('estimated_value')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('route_id')
                    ->relationship('route', 'name'),
                TextInput::make('owner'),
                TextInput::make('notes'),
            ]);
    }
}
