<x-filament-panels::page>
    @php($s = $this->summary)

    {{-- Summary tiles --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Bottles in the field</div>
            <div class="mt-1 text-3xl font-semibold tracking-tight text-sky-600 tabular-nums">{{ number_format($s['outstanding']) }}</div>
            <div class="mt-1 text-xs text-gray-400">{{ number_format($s['issued']) }} issued · {{ number_format($s['returned']) }} returned</div>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Deposit held</div>
            <div class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 tabular-nums dark:text-white">TZS {{ number_format($s['deposit_held']) }}</div>
            <div class="mt-1 text-xs text-gray-400">refundable against returns</div>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Return rate</div>
            <div class="mt-1 text-3xl font-semibold tracking-tight text-gray-900 tabular-nums dark:text-white">{{ $s['issued'] > 0 ? round($s['returned'] / $s['issued'] * 100) : 0 }}%</div>
            <div class="mt-1 text-xs text-gray-400">empties recovered</div>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="text-sm text-gray-500 dark:text-gray-400">Accounts to chase</div>
            <div class="mt-1 text-3xl font-semibold tracking-tight {{ $s['high'] > 0 ? 'text-rose-500' : 'text-emerald-500' }} tabular-nums">{{ $s['high'] }}</div>
            <div class="mt-1 text-xs text-gray-400">100+ bottles unreturned</div>
        </div>
    </div>

    {{-- Per-customer ledger --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-200 bg-gray-50/80 text-gray-600 dark:border-white/10 dark:bg-gray-800 dark:text-gray-300">
                <tr>
                    <th class="px-5 py-3 font-medium">Customer</th>
                    <th class="px-5 py-3 font-medium">Route</th>
                    <th class="px-5 py-3 text-right font-medium">Issued</th>
                    <th class="px-5 py-3 text-right font-medium">Returned</th>
                    <th class="px-5 py-3 text-right font-medium">Outstanding</th>
                    <th class="px-5 py-3 text-right font-medium">Deposit held (TZS)</th>
                    <th class="px-5 py-3 font-medium">Last movement</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                @forelse ($this->rows as $row)
                    <tr class="text-gray-800 transition hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/5">
                        <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $row->customer }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $row->route ?? '—' }}</td>
                        <td class="px-5 py-3 text-right tabular-nums">{{ number_format($row->issued) }}</td>
                        <td class="px-5 py-3 text-right tabular-nums text-gray-500">{{ number_format($row->returned) }}</td>
                        <td class="px-5 py-3 text-right">
                            <span @class([
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold tabular-nums',
                                'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $row->outstanding < 50,
                                'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' => $row->outstanding >= 50 && $row->outstanding < 100,
                                'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' => $row->outstanding >= 100,
                            ])>{{ number_format($row->outstanding) }}</span>
                        </td>
                        <td class="px-5 py-3 text-right tabular-nums">{{ number_format($row->deposit_held) }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ \Illuminate\Support\Carbon::parse($row->last_movement)->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No container movements yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
