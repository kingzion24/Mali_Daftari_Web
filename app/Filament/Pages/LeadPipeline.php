<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Lead;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class LeadPipeline extends Page
{
    protected string $view = 'filament.pages.lead-pipeline';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-view-columns';

    protected static string|\UnitEnum|null $navigationGroup = 'CRM';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Sales Pipeline';

    /** Ordered pipeline stages. */
    public const STAGES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'proposal' => 'Proposal',
        'won' => 'Won',
        'lost' => 'Lost',
    ];

    public function getColumnsProperty(): array
    {
        $byStage = Lead::query()
            ->with('route')
            ->orderByDesc('estimated_value')
            ->get()
            ->groupBy('stage');

        $columns = [];
        foreach (self::STAGES as $key => $label) {
            /** @var Collection $leads */
            $leads = $byStage->get($key, collect());
            $columns[$key] = [
                'label' => $label,
                'leads' => $leads,
                'count' => $leads->count(),
                'value' => (float) $leads->sum('estimated_value'),
            ];
        }

        return $columns;
    }

    public function move(int $leadId, string $direction): void
    {
        $keys = array_keys(self::STAGES);
        $lead = Lead::find($leadId);

        if (! $lead) {
            return;
        }

        $index = array_search($lead->stage, $keys, true);
        if ($index === false) {
            return;
        }

        $newIndex = $direction === 'forward' ? $index + 1 : $index - 1;
        if ($newIndex < 0 || $newIndex >= count($keys)) {
            return;
        }

        $lead->update(['stage' => $keys[$newIndex]]);
    }
}
