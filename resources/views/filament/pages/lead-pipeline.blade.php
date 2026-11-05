<x-filament-panels::page>
    @php($stageColors = [
        'new' => 'border-gray-400',
        'contacted' => 'border-sky-400',
        'qualified' => 'border-cyan-400',
        'proposal' => 'border-amber-400',
        'won' => 'border-emerald-500',
        'lost' => 'border-rose-400',
    ])

    <div class="flex gap-4 overflow-x-auto pb-4">
        @foreach ($this->columns as $key => $col)
            <div class="flex w-72 flex-shrink-0 flex-col rounded-xl bg-gray-50 ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                {{-- Column header --}}
                <div class="flex items-center justify-between border-b-2 {{ $stageColors[$key] ?? 'border-gray-400' }} px-3 py-3">
                    <div class="font-semibold text-gray-900 dark:text-white">{{ $col['label'] }}</div>
                    <div class="text-right">
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $col['count'] }} leads</div>
                        <div class="text-xs font-semibold text-sky-600">TZS {{ number_format($col['value']) }}</div>
                    </div>
                </div>

                {{-- Cards --}}
                <div class="flex flex-1 flex-col gap-2 p-2">
                    @forelse ($col['leads'] as $lead)
                        <div class="rounded-lg bg-white p-3 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10">
                            <div class="flex items-start justify-between gap-2">
                                <div class="font-medium leading-tight text-gray-900 dark:text-white">{{ $lead->name }}</div>
                                <span class="shrink-0 rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium uppercase tracking-wide text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ str_replace('_', ' ', $lead->source) }}</span>
                            </div>
                            <div class="mt-1 text-sm font-semibold text-sky-600">TZS {{ number_format((float) $lead->estimated_value) }}</div>
                            <div class="mt-1 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <span>{{ $lead->contact_name ?? '—' }}</span>
                                @if ($lead->route)
                                    <span>· {{ $lead->route->name }}</span>
                                @endif
                            </div>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-xs text-gray-400">{{ $lead->owner }}</span>
                                <div class="flex gap-1">
                                    <button type="button" wire:click="move({{ $lead->id }}, 'backward')"
                                        title="Move back"
                                        class="rounded-md bg-gray-100 px-2 py-1 text-xs text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">&larr;</button>
                                    <button type="button" wire:click="move({{ $lead->id }}, 'forward')"
                                        title="Move forward"
                                        class="rounded-md bg-sky-100 px-2 py-1 text-xs text-sky-700 hover:bg-sky-200 dark:bg-sky-900 dark:text-sky-200">&rarr;</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-gray-200 px-3 py-6 text-center text-xs text-gray-400 dark:border-gray-700">
                            No leads
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
