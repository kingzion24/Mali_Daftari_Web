<x-filament-panels::page>
    {{-- Controls --}}
    <div class="fi-section rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 print:hidden">
        <div class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">From</label>
                <input type="date" wire:model.live="fromDate"
                    class="mt-1 rounded-lg border-gray-300 text-sm shadow-sm dark:bg-gray-800 dark:border-gray-700" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">To</label>
                <input type="date" wire:model.live="toDate"
                    class="mt-1 rounded-lg border-gray-300 text-sm shadow-sm dark:bg-gray-800 dark:border-gray-700" />
            </div>
            <div class="ml-auto flex gap-2">
                <button type="button" wire:click="exportCsv"
                    class="inline-flex items-center gap-1 rounded-lg bg-sky-600 px-3 py-2 text-sm font-semibold text-white hover:bg-sky-500">
                    Download CSV
                </button>
                <button type="button" onclick="window.print()"
                    class="inline-flex items-center gap-1 rounded-lg bg-gray-700 px-3 py-2 text-sm font-semibold text-white hover:bg-gray-600">
                    Print / Save as PDF
                </button>
            </div>
        </div>
    </div>

    {{-- Report header (shown on print) --}}
    <div class="hidden print:block">
        <h1 class="text-xl font-bold">Living Water — Sales Report</h1>
        <p class="text-sm text-gray-600">Period: {{ $this->fromDate }} to {{ $this->toDate }}</p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        @php($s = $this->summary)
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Deliveries</div>
            <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($s['count']) }}</div>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Total revenue</div>
            <div class="mt-1 text-2xl font-bold text-sky-600">TZS {{ number_format($s['revenue']) }}</div>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Average order</div>
            <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">TZS {{ number_format($s['average']) }}</div>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Top route</div>
            <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $s['topRoute'] }}</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-200 bg-gray-50 text-gray-600 dark:border-white/10 dark:bg-gray-800 dark:text-gray-300">
                <tr>
                    <th class="px-4 py-3 font-medium">Order No</th>
                    <th class="px-4 py-3 font-medium">Date</th>
                    <th class="px-4 py-3 font-medium">Customer</th>
                    <th class="px-4 py-3 font-medium">Route</th>
                    <th class="px-4 py-3 text-right font-medium">Total (TZS)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                @forelse ($this->rows as $o)
                    <tr class="text-gray-800 dark:text-gray-200">
                        <td class="px-4 py-2 font-mono text-xs">{{ $o->order_no }}</td>
                        <td class="px-4 py-2">{{ $o->order_date?->toFormattedDateString() }}</td>
                        <td class="px-4 py-2">{{ $o->customer?->name }}</td>
                        <td class="px-4 py-2">{{ $o->route?->name }}</td>
                        <td class="px-4 py-2 text-right tabular-nums">{{ number_format((float) $o->total) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-400">No deliveries in this period.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($this->rows->isNotEmpty())
                <tfoot class="border-t-2 border-gray-200 font-semibold text-gray-900 dark:border-white/10 dark:text-white">
                    <tr>
                        <td class="px-4 py-3" colspan="4">Total — {{ $this->rows->count() }} deliveries</td>
                        <td class="px-4 py-3 text-right tabular-nums">TZS {{ number_format((float) $this->rows->sum('total')) }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</x-filament-panels::page>
