<?php

namespace App\Filament\Resources\Tasks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                DatePicker::make('due_date'),
                Toggle::make('done')
                    ->required(),
                Select::make('customer_id')
                    ->relationship('customer', 'name'),
                Select::make('lead_id')
                    ->relationship('lead', 'name'),
                TextInput::make('assigned_to'),
                TextInput::make('notes'),
            ]);
    }
}
