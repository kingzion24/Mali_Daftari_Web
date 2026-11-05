<?php

namespace App\Filament\Resources\StandingOrders;

use App\Filament\Resources\StandingOrders\Pages\CreateStandingOrder;
use App\Filament\Resources\StandingOrders\Pages\EditStandingOrder;
use App\Filament\Resources\StandingOrders\Pages\ListStandingOrders;
use App\Filament\Resources\StandingOrders\Schemas\StandingOrderForm;
use App\Filament\Resources\StandingOrders\Tables\StandingOrdersTable;
use App\Models\StandingOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StandingOrderResource extends Resource
{
    protected static ?string $model = StandingOrder::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    protected static string|\UnitEnum|null $navigationGroup = 'Distribution';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return StandingOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StandingOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStandingOrders::route('/'),
            'create' => CreateStandingOrder::route('/create'),
            'edit' => EditStandingOrder::route('/{record}/edit'),
        ];
    }
}
