<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Order;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalesReport extends Page
{
    protected string $view = 'filament.pages.sales-report';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string|\UnitEnum|null $navigationGroup = 'Sales & Finance';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Sales Report';

    public ?string $fromDate = null;

    public ?string $toDate = null;

    public function mount(): void
    {
        $this->fromDate = Carbon::now()->subDays(30)->toDateString();
        $this->toDate = Carbon::now()->toDateString();
    }

    protected function reportQuery()
    {
        return Order::query()
            ->where('status', 'delivered')
            ->when($this->fromDate, fn ($q) => $q->whereDate('order_date', '>=', $this->fromDate))
            ->when($this->toDate, fn ($q) => $q->whereDate('order_date', '<=', $this->toDate))
            ->with(['customer', 'route'])
            ->orderBy('order_date');
    }

    public function getRowsProperty(): Collection
    {
        return $this->reportQuery()->get();
    }

    public function getSummaryProperty(): array
    {
        $rows = $this->rows;
        $count = $rows->count();
        $revenue = (float) $rows->sum('total');

        return [
            'count' => $count,
            'revenue' => $revenue,
            'average' => $count > 0 ? $revenue / $count : 0.0,
            'topRoute' => $rows->groupBy(fn ($o) => $o->route?->name ?? '—')
                ->map(fn ($g) => (float) $g->sum('total'))
                ->sortDesc()
                ->keys()
                ->first() ?? '—',
        ];
    }

    public function exportCsv(): StreamedResponse
    {
        $rows = $this->rows;
        $from = $this->fromDate;
        $to = $this->toDate;

        return response()->streamDownload(function () use ($rows, $from, $to): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Living Water — Sales Report']);
            fputcsv($out, ['Period', $from.' to '.$to]);
            fputcsv($out, []);
            fputcsv($out, ['Order No', 'Date', 'Customer', 'Route', 'Status', 'Total (TZS)']);
            foreach ($rows as $o) {
                fputcsv($out, [
                    $o->order_no,
                    $o->order_date?->toDateString(),
                    $o->customer?->name,
                    $o->route?->name,
                    $o->status,
                    number_format((float) $o->total, 2, '.', ''),
                ]);
            }
            fputcsv($out, []);
            fputcsv($out, ['', '', '', '', 'TOTAL', number_format((float) $rows->sum('total'), 2, '.', '')]);
            fclose($out);
        }, "living-water-sales_{$from}_to_{$to}.csv", ['Content-Type' => 'text/csv']);
    }
}
