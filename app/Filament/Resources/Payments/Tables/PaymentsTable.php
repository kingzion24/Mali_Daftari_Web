<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('paid_on')
                    ->label('Paid on')
                    ->date('M j, Y')
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),
                TextColumn::make('invoice.invoice_no')
                    ->label('Invoice')
                    ->searchable(),
                TextColumn::make('amount')
                    ->money('TZS')
                    ->sortable()
                    ->summarize(Sum::make()->money('TZS')),
                TextColumn::make('method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'mpesa' => 'info',
                        'bank' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('reference')
                    ->toggleable(),
            ])
            ->defaultSort('paid_on', 'desc')
            ->filters([
                SelectFilter::make('method')
                    ->options([
                        'cash' => 'Cash',
                        'mpesa' => 'M-Pesa',
                        'bank' => 'Bank',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
