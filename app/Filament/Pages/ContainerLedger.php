<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Product;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ContainerLedger extends Page
{
    protected string $view = 'filament.pages.container-ledger';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static string|\UnitEnum|null $navigationGroup = 'Distribution';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Container Ledger';

    public function getDepositProperty(): float
    {
        return (float) (Product::where('sku', 'LW-20L')->value('deposit_amount') ?? 12000);
    }

    public function getRowsProperty(): Collection
    {
        $deposit = $this->deposit;

        return DB::table('container_transactions as ct')
            ->join('customers as c', 'c.id', '=', 'ct.customer_id')
            ->leftJoin('routes as r', 'r.id', '=', 'c.route_id')
            ->selectRaw("c.id as customer_id, c.name as customer, c.type as customer_type, r.name as route")
            ->selectRaw("SUM(CASE WHEN ct.type = 'issued' THEN ct.quantity ELSE 0 END) as issued")
            ->selectRaw("SUM(CASE WHEN ct.type = 'returned' THEN ct.quantity ELSE 0 END) as returned")
            ->selectRaw('MAX(ct.txn_date) as last_movement')
            ->groupBy('c.id', 'c.name', 'c.type', 'r.name')
            ->get()
            ->map(function ($row) use ($deposit) {
                $outstanding = (int) $row->issued - (int) $row->returned;
                $row->outstanding = $outstanding;
                $row->deposit_held = $outstanding * $deposit;

                return $row;
            })
            ->sortByDesc('outstanding')
            ->values();
    }

    public function getSummaryProperty(): array
    {
        $rows = $this->rows;
        $outstanding = (int) $rows->sum('outstanding');

        return [
            'customers' => $rows->count(),
            'issued' => (int) $rows->sum('issued'),
            'returned' => (int) $rows->sum('returned'),
            'outstanding' => $outstanding,
            'deposit_held' => $outstanding * $this->deposit,
            'high' => $rows->where('outstanding', '>=', 100)->count(),
        ];
    }
}
