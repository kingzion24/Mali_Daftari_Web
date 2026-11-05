<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Business')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'distributor' => 'info',
                        'hotel' => 'warning',
                        'office' => 'primary',
                        'retailer' => 'success',
                        'household' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('route.name')
                    ->label('Route')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                TextColumn::make('price_tier')
                    ->label('Tier')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('phone')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('credit_limit')
                    ->label('Credit limit')
                    ->money('TZS')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('balance')
                    ->label('Balance owed')
                    ->money('TZS')
                    ->sortable()
                    ->color(fn ($state): string => (float) $state > 0 ? 'danger' : 'success'),
            ])
            ->defaultSort('name')
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'retailer' => 'Retailer',
                        'office' => 'Office',
                        'hotel' => 'Hotel',
                        'household' => 'Household',
                        'distributor' => 'Distributor',
                    ]),
                SelectFilter::make('route_id')
                    ->label('Route')
                    ->relationship('route', 'name'),
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
