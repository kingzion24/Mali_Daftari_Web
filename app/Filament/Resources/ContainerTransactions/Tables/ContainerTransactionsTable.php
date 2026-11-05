<?php

namespace App\Filament\Resources\ContainerTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ContainerTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('txn_date')
                    ->label('Date')
                    ->date('M j, Y')
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Container')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'issued' => 'info',
                        'returned' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('deposit_delta')
                    ->label('Deposit change')
                    ->money('TZS')
                    ->sortable(),
                TextColumn::make('notes')
                    ->toggleable()
                    ->limit(40),
            ])
            ->defaultSort('txn_date', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'issued' => 'Issued',
                        'returned' => 'Returned',
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
