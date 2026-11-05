<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;

class SalesTrendChart extends ChartWidget
{
    protected ?string $heading = 'Sales trend — last 12 weeks';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $labels = [];
        $values = [];

        for ($w = 11; $w >= 0; $w--) {
            $start = Carbon::now()->subWeeks($w)->startOfWeek();
            $end = (clone $start)->endOfWeek();

            $total = (float) Order::where('status', 'delivered')
                ->whereBetween('order_date', [$start->toDateString(), $end->toDateString()])
                ->sum('total');

            $labels[] = $start->format('d M');
            $values[] = round($total / 1000, 1); // thousands of TZS
        }

        return [
            'datasets' => [
                [
                    'label' => 'Delivered sales (TZS 000s)',
                    'data' => $values,
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
