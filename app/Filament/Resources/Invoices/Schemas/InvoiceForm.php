<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invoice_no')
                    ->label('Invoice number')
                    ->disabled()
                    ->dehydrated(false)
                    ->placeholder('Generated automatically on save'),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('order_id')
                    ->relationship('order', 'order_no'),
                DatePicker::make('issue_date')
                    ->default(now())
                    ->required(),
                DatePicker::make('due_date')
                    ->default(now()->addDays(14))
                    ->required(),
                TextInput::make('amount')
                    ->label('Amount (TZS)')
                    ->numeric()
                    ->prefix('TZS')
                    ->default(0),
                TextInput::make('amount_paid')
                    ->label('Amount paid (TZS)')
                    ->numeric()
                    ->prefix('TZS')
                    ->default(0),
                Select::make('status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                    ])
                    ->default('unpaid')
                    ->required(),
            ]);
    }
}
