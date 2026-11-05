<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\ContainerTransaction;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Truck;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LivingWaterStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Business at a glance';

    protected function getStats(): array
    {
        $since = Carbon::now()->subDays(30);

        $revenue = (float) Order::where('status', 'delivered')
            ->where('order_date', '>=', $since->toDateString())
            ->sum('total');

        $deliveries = Order::where('status', 'delivered')
            ->where('order_date', '>=', $since->toDateString())
            ->count();

        $receivables = (float) Invoice::whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->sum(DB::raw('amount - amount_paid'));

        $overdue = Invoice::where('status', 'overdue')->count();

        // Returnable containers still out in the field (issued - returned).
        $issued = (int) ContainerTransaction::where('type', 'issued')->sum('quantity');
        $returned = (int) ContainerTransaction::where('type', 'returned')->sum('quantity');
        $inField = $issued - $returned;

        $trucks = Truck::count();

        return [
            Stat::make('Revenue (30 days)', 'TZS '.number_format($revenue))
                ->description($deliveries.' deliveries completed')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Receivables outstanding', 'TZS '.number_format($receivables))
                ->description($overdue.' invoices overdue')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($overdue > 0 ? 'warning' : 'info'),

            Stat::make('20L containers in the field', number_format($inField))
                ->description($issued.' issued · '.$returned.' returned')
                ->descriptionIcon('heroicon-m-arrows-right-left')
                ->color('primary'),

            Stat::make('Active delivery trucks', (string) $trucks)
                ->description('Across all routes')
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),
        ];
    }
}
