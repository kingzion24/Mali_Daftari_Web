<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class ContainersByRoute extends ChartWidget
{
    protected ?string $heading = 'Delivered sales by route (30 days)';

    protected function getData(): array
    {
        $rows = Order::query()
            ->where('orders.status', 'delivered')
            ->where('orders.order_date', '>=', now()->subDays(30)->toDateString())
            ->join('routes', 'routes.id', '=', 'orders.route_id')
            ->groupBy('routes.name')
            ->orderByDesc('total_sales')
            ->selectRaw('routes.name as route_name, SUM(orders.total) as total_sales')
            ->get();

        $palette = ['#0ea5e9', '#06b6d4', '#10b981', '#f59e0b', '#f43f5e', '#8b5cf6'];

        return [
            'datasets' => [
                [
                    'label' => 'Sales (TZS)',
                    'data' => $rows->map(fn ($r) => (float) $r->total_sales)->all(),
                    'backgroundColor' => array_slice($palette, 0, max(1, $rows->count())),
                ],
            ],
            'labels' => $rows->pluck('route_name')->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
