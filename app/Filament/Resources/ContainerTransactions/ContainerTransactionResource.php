<?php

namespace App\Filament\Resources\ContainerTransactions;

use App\Filament\Resources\ContainerTransactions\Pages\CreateContainerTransaction;
use App\Filament\Resources\ContainerTransactions\Pages\EditContainerTransaction;
use App\Filament\Resources\ContainerTransactions\Pages\ListContainerTransactions;
use App\Filament\Resources\ContainerTransactions\Schemas\ContainerTransactionForm;
use App\Filament\Resources\ContainerTransactions\Tables\ContainerTransactionsTable;
use App\Models\ContainerTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContainerTransactionResource extends Resource
{
    protected static ?string $model = ContainerTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static string|\UnitEnum|null $navigationGroup = 'Distribution';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ContainerTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContainerTransactionsTable::configure($table);
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
            'index' => ListContainerTransactions::route('/'),
            'create' => CreateContainerTransaction::route('/create'),
            'edit' => EditContainerTransaction::route('/{record}/edit'),
        ];
    }
}
