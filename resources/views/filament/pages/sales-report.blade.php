<x-filament-panels::page>
    {{-- Controls --}}
    <div class="lw-panel lw-toolbar print:hidden" style="margin-bottom:18px">
        <div class="lw-field">
            <label>From</label>
            <input type="date" wire:model.live="fromDate" class="lw-input" />
        </div>
        <div class="lw-field">
            <label>To</label>
            <input type="date" wire:model.live="toDate" class="lw-input" />
        </div>
        <div class="lw-spacer"></div>
        <button type="button" wire:click="exportCsv" class="lw-btn lw-btn-primary">Download CSV</button>
        <button type="button" onclick="window.print()" class="lw-btn lw-btn-ghost">Print / Save as PDF</button>
    </div>

    {{-- Print-only header --}}
    <div class="print-head" style="display:none">
        <h2 style="font-size:20px;font-weight:600">Living Water — Sales Report</h2>
        <p style="color:#86868b">Period: {{ $this->fromDate }} to {{ $this->toDate }}</p>
    </div>

    @php($s = $this->summary)
    <div class="lw-stats" style="--lw-cols:4">
        <div class="lw-stat">
            <div class="lw-stat-label">Deliveries</div>
            <div class="lw-stat-value lw-num">{{ number_format($s['count']) }}</div>
        </div>
        <div class="lw-stat">
            <div class="lw-stat-label">Total revenue</div>
            <div class="lw-stat-value lw-accent lw-num">TZS {{ number_format($s['revenue']) }}</div>
        </div>
        <div class="lw-stat">
            <div class="lw-stat-label">Average order</div>
            <div class="lw-stat-value lw-num">TZS {{ number_format($s['average']) }}</div>
        </div>
        <div class="lw-stat">
            <div class="lw-stat-label">Top route</div>
            <div class="lw-stat-value" style="font-size:18px;letter-spacing:-0.01em">{{ $s['topRoute'] }}</div>
        </div>
    </div>

    <div class="lw-table-wrap lw-mt">
        <table class="lw-table">
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Route</th>
                    <th class="r">Total (TZS)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->rows as $o)
                    <tr>
                        <td class="lw-mono">{{ $o->order_no }}</td>
                        <td>{{ $o->order_date?->format('M j, Y') }}</td>
                        <td class="lw-strong">{{ $o->customer?->name }}</td>
                        <td class="lw-dim">{{ $o->route?->name }}</td>
                        <td class="r lw-num">{{ number_format((float) $o->total) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="padding:40px;text-align:center;color:#86868b">No deliveries in this period.</td></tr>
                @endforelse
            </tbody>
            @if ($this->rows->isNotEmpty())
                <tfoot>
                    <tr>
                        <td colspan="4">Total — {{ $this->rows->count() }} deliveries</td>
                        <td class="r lw-num">TZS {{ number_format((float) $this->rows->sum('total')) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    <style>
        @media print {
            .fi-sidebar, .fi-topbar, .print\:hidden { display: none !important; }
            .print-head { display: block !important; margin-bottom: 16px; }
        }
    </style>
</x-filament-panels::page>
