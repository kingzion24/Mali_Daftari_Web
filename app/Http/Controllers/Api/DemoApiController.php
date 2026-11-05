<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Product;
use App\Models\StandingOrder;
use App\Models\Task;
use App\Models\Truck;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DemoApiController extends Controller
{
    private const STAGES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'proposal' => 'Proposal',
        'won' => 'Won',
        'lost' => 'Lost',
    ];

    public function dashboard(): JsonResponse
    {
        $since = Carbon::now()->subDays(30)->toDateString();

        $revenue = (float) Order::where('status', 'delivered')->where('order_date', '>=', $since)->sum('total');
        $deliveries = Order::where('status', 'delivered')->where('order_date', '>=', $since)->count();
        $receivables = (float) Invoice::whereIn('status', ['unpaid', 'partial', 'overdue'])->sum(DB::raw('amount - amount_paid'));
        $overdue = Invoice::where('status', 'overdue')->count();

        $issued = (int) DB::table('container_transactions')->where('type', 'issued')->sum('quantity');
        $returned = (int) DB::table('container_transactions')->where('type', 'returned')->sum('quantity');

        // Weekly sales trend (12 weeks)
        $trend = [];
        for ($w = 11; $w >= 0; $w--) {
            $start = Carbon::now()->subWeeks($w)->startOfWeek();
            $end = (clone $start)->endOfWeek();
            $trend[] = [
                'label' => $start->format('d M'),
                'value' => (float) Order::where('status', 'delivered')
                    ->whereBetween('order_date', [$start->toDateString(), $end->toDateString()])
                    ->sum('total'),
            ];
        }

        $byRoute = Order::query()
            ->where('orders.status', 'delivered')
            ->where('orders.order_date', '>=', $since)
            ->join('routes', 'routes.id', '=', 'orders.route_id')
            ->groupBy('routes.name')
            ->orderByDesc('total_sales')
            ->selectRaw('routes.name as route, SUM(orders.total) as total_sales')
            ->get()
            ->map(fn ($r) => ['route' => $r->route, 'value' => (float) $r->total_sales]);

        $overdueInvoices = Invoice::where('status', 'overdue')
            ->with('customer:id,name')
            ->orderByDesc('amount')
            ->limit(6)
            ->get()
            ->map(fn (Invoice $i) => [
                'invoice_no' => $i->invoice_no,
                'customer' => $i->customer?->name,
                'amount' => (float) $i->amount,
                'due_date' => $i->due_date?->toDateString(),
            ]);

        $upcomingStanding = StandingOrder::where('active', true)
            ->with(['customer:id,name', 'route:id,name'])
            ->orderBy('next_delivery')
            ->limit(6)
            ->get()
            ->map(fn (StandingOrder $s) => [
                'customer' => $s->customer?->name,
                'route' => $s->route?->name,
                'frequency' => $s->frequency,
                'next_delivery' => $s->next_delivery?->toDateString(),
            ]);

        return response()->json([
            'kpis' => [
                'revenue' => $revenue,
                'deliveries' => $deliveries,
                'receivables' => $receivables,
                'overdue' => $overdue,
                'containers_out' => $issued - $returned,
                'issued' => $issued,
                'returned' => $returned,
                'trucks' => Truck::count(),
                'open_tasks' => Task::where('done', false)->count(),
            ],
            'trend' => $trend,
            'by_route' => $byRoute,
            'overdue_invoices' => $overdueInvoices,
            'upcoming_standing' => $upcomingStanding,
        ]);
    }

    public function invoices(): JsonResponse
    {
        $invoices = Invoice::query()
            ->with('customer:id,name')
            ->orderByDesc('issue_date')
            ->limit(200)
            ->get()
            ->map(fn (Invoice $i) => [
                'invoice_no' => $i->invoice_no,
                'customer' => $i->customer?->name,
                'issue_date' => $i->issue_date?->toDateString(),
                'due_date' => $i->due_date?->toDateString(),
                'amount' => (float) $i->amount,
                'amount_paid' => (float) $i->amount_paid,
                'status' => $i->status,
            ]);

        return response()->json(['invoices' => $invoices]);
    }

    public function standingOrders(): JsonResponse
    {
        $orders = StandingOrder::query()
            ->with(['customer:id,name', 'route:id,name', 'items'])
            ->orderBy('next_delivery')
            ->get()
            ->map(fn (StandingOrder $s) => [
                'customer' => $s->customer?->name,
                'route' => $s->route?->name,
                'frequency' => $s->frequency,
                'next_delivery' => $s->next_delivery?->toDateString(),
                'active' => (bool) $s->active,
                'bottles' => (int) $s->items->sum('quantity'),
            ]);

        return response()->json(['standing_orders' => $orders]);
    }

    public function orders(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->with(['customer:id,name', 'route:id,name', 'truck:id,name'])
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->when($request->query('route'), fn ($q, $r) => $q->where('route_id', $r))
            ->when($request->query('search'), fn ($q, $s) => $q->where('order_no', 'like', "%{$s}%"))
            ->orderByDesc('order_date')
            ->limit(200)
            ->get()
            ->map(fn (Order $o) => [
                'order_no' => $o->order_no,
                'customer' => $o->customer?->name,
                'route' => $o->route?->name,
                'truck' => $o->truck?->name,
                'order_date' => $o->order_date?->toDateString(),
                'delivery_date' => $o->delivery_date?->toDateString(),
                'status' => $o->status,
                'total' => (float) $o->total,
            ]);

        return response()->json(['orders' => $orders]);
    }

    public function customers(): JsonResponse
    {
        $customers = Customer::query()
            ->with('route:id,name')
            ->orderBy('name')
            ->get()
            ->map(fn (Customer $c) => [
                'name' => $c->name,
                'type' => $c->type,
                'route' => $c->route?->name,
                'price_tier' => $c->price_tier,
                'phone' => $c->phone,
                'credit_limit' => (float) $c->credit_limit,
                'balance' => (float) $c->balance,
            ]);

        return response()->json(['customers' => $customers]);
    }

    public function containers(): JsonResponse
    {
        $deposit = (float) (Product::where('sku', 'LW-20L')->value('deposit_amount') ?? 12000);

        $rows = DB::table('container_transactions as ct')
            ->join('customers as c', 'c.id', '=', 'ct.customer_id')
            ->leftJoin('routes as r', 'r.id', '=', 'c.route_id')
            ->selectRaw('c.name as customer, c.type as customer_type, r.name as route')
            ->selectRaw("SUM(CASE WHEN ct.type = 'issued' THEN ct.quantity ELSE 0 END) as issued")
            ->selectRaw("SUM(CASE WHEN ct.type = 'returned' THEN ct.quantity ELSE 0 END) as returned")
            ->selectRaw('MAX(ct.txn_date) as last_movement')
            ->groupBy('c.id', 'c.name', 'c.type', 'r.name')
            ->get()
            ->map(function ($row) use ($deposit) {
                $outstanding = (int) $row->issued - (int) $row->returned;

                return [
                    'customer' => $row->customer,
                    'route' => $row->route,
                    'issued' => (int) $row->issued,
                    'returned' => (int) $row->returned,
                    'outstanding' => $outstanding,
                    'deposit_held' => $outstanding * $deposit,
                    'last_movement' => $row->last_movement,
                ];
            })
            ->sortByDesc('outstanding')
            ->values();

        $outstanding = (int) $rows->sum('outstanding');

        return response()->json([
            'summary' => [
                'customers' => $rows->count(),
                'issued' => (int) $rows->sum('issued'),
                'returned' => (int) $rows->sum('returned'),
                'outstanding' => $outstanding,
                'deposit_held' => $outstanding * $deposit,
                'high' => $rows->where('outstanding', '>=', 100)->count(),
            ],
            'rows' => $rows,
        ]);
    }

    public function pipeline(): JsonResponse
    {
        $byStage = Lead::query()->orderByDesc('estimated_value')->get()->groupBy('stage');

        $columns = [];
        foreach (self::STAGES as $key => $label) {
            $leads = $byStage->get($key, collect());
            $columns[] = [
                'key' => $key,
                'label' => $label,
                'count' => $leads->count(),
                'value' => (float) $leads->sum('estimated_value'),
                'leads' => $leads->map(fn (Lead $l) => [
                    'id' => $l->id,
                    'name' => $l->name,
                    'contact_name' => $l->contact_name,
                    'source' => $l->source,
                    'estimated_value' => (float) $l->estimated_value,
                    'owner' => $l->owner,
                    'route' => $l->route?->name,
                ])->values(),
            ];
        }

        return response()->json(['columns' => $columns]);
    }

    public function moveLead(Request $request, Lead $lead): JsonResponse
    {
        $keys = array_keys(self::STAGES);
        $index = array_search($lead->stage, $keys, true);
        $direction = $request->input('direction');

        if ($index !== false) {
            $newIndex = $direction === 'forward' ? $index + 1 : $index - 1;
            if ($newIndex >= 0 && $newIndex < count($keys)) {
                $lead->update(['stage' => $keys[$newIndex]]);
            }
        }

        return response()->json(['stage' => $lead->fresh()->stage]);
    }

    public function report(Request $request): JsonResponse
    {
        [$from, $to] = $this->reportRange($request);
        $rows = $this->reportRows($from, $to);
        $count = $rows->count();
        $revenue = (float) $rows->sum('total');

        return response()->json([
            'from' => $from,
            'to' => $to,
            'summary' => [
                'count' => $count,
                'revenue' => $revenue,
                'average' => $count > 0 ? $revenue / $count : 0,
            ],
            'rows' => $rows,
        ]);
    }

    public function reportCsv(Request $request): StreamedResponse
    {
        [$from, $to] = $this->reportRange($request);
        $rows = $this->reportRows($from, $to);

        return response()->streamDownload(function () use ($rows, $from, $to): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Living Water — Sales Report']);
            fputcsv($out, ['Period', $from.' to '.$to]);
            fputcsv($out, []);
            fputcsv($out, ['Order No', 'Date', 'Customer', 'Route', 'Total (TZS)']);
            foreach ($rows as $r) {
                fputcsv($out, [$r['order_no'], $r['order_date'], $r['customer'], $r['route'], number_format((float) $r['total'], 2, '.', '')]);
            }
            fputcsv($out, []);
            fputcsv($out, ['', '', '', 'TOTAL', number_format((float) $rows->sum('total'), 2, '.', '')]);
            fclose($out);
        }, "living-water-sales_{$from}_to_{$to}.csv", ['Content-Type' => 'text/csv']);
    }

    /** @return array{0:string,1:string} */
    private function reportRange(Request $request): array
    {
        $from = $request->query('from', Carbon::now()->subDays(30)->toDateString());
        $to = $request->query('to', Carbon::now()->toDateString());

        return [$from, $to];
    }

    private function reportRows(string $from, string $to)
    {
        return Order::query()
            ->where('status', 'delivered')
            ->whereDate('order_date', '>=', $from)
            ->whereDate('order_date', '<=', $to)
            ->with(['customer:id,name', 'route:id,name'])
            ->orderBy('order_date')
            ->get()
            ->map(fn (Order $o) => [
                'order_no' => $o->order_no,
                'order_date' => $o->order_date?->toDateString(),
                'customer' => $o->customer?->name,
                'route' => $o->route?->name,
                'total' => (float) $o->total,
            ]);
    }
}
