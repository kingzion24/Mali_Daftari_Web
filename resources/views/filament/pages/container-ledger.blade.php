<x-filament-panels::page>
    @php($s = $this->summary)

    <div class="lw-stats">
        <div class="lw-stat">
            <div class="lw-stat-label">Bottles in the field</div>
            <div class="lw-stat-value lw-accent">{{ number_format($s['outstanding']) }}</div>
            <div class="lw-stat-meta">{{ number_format($s['issued']) }} issued · {{ number_format($s['returned']) }} returned</div>
        </div>
        <div class="lw-stat">
            <div class="lw-stat-label">Deposit held</div>
            <div class="lw-stat-value">TZS {{ number_format($s['deposit_held']) }}</div>
            <div class="lw-stat-meta">refundable against returns</div>
        </div>
        <div class="lw-stat">
            <div class="lw-stat-label">Return rate</div>
            <div class="lw-stat-value">{{ $s['issued'] > 0 ? round($s['returned'] / $s['issued'] * 100) : 0 }}%</div>
            <div class="lw-stat-meta">empties recovered</div>
        </div>
        <div class="lw-stat">
            <div class="lw-stat-label">Accounts to chase</div>
            <div class="lw-stat-value {{ $s['high'] > 0 ? 'lw-rose' : 'lw-emerald' }}">{{ $s['high'] }}</div>
            <div class="lw-stat-meta">100+ bottles unreturned</div>
        </div>
    </div>

    <div class="lw-table-wrap lw-mt">
        <table class="lw-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Route</th>
                    <th class="r">Issued</th>
                    <th class="r">Returned</th>
                    <th class="r">Outstanding</th>
                    <th class="r">Deposit held (TZS)</th>
                    <th>Last movement</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->rows as $row)
                    <tr>
                        <td class="lw-strong">{{ $row->customer }}</td>
                        <td class="lw-dim">{{ $row->route ?? '—' }}</td>
                        <td class="r lw-num">{{ number_format($row->issued) }}</td>
                        <td class="r lw-num lw-dim">{{ number_format($row->returned) }}</td>
                        <td class="r">
                            <span class="lw-badge {{ $row->outstanding >= 100 ? 'rose' : ($row->outstanding >= 50 ? 'amber' : 'green') }} lw-num">{{ number_format($row->outstanding) }}</span>
                        </td>
                        <td class="r lw-num">{{ number_format($row->deposit_held) }}</td>
                        <td class="lw-dim">{{ \Illuminate\Support\Carbon::parse($row->last_movement)->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="padding:40px;text-align:center;color:#86868b">No container movements yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
