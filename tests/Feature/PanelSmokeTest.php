<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Pages\LeadPipeline;
use App\Filament\Pages\SalesReport;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PanelSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->actingAs(User::where('email', 'demo@livingwater.co.tz')->firstOrFail());
    }

    public function test_dashboard_loads(): void
    {
        $this->get('/admin')->assertOk();
    }

    public function test_resource_pages_load(): void
    {
        foreach (['orders', 'customers', 'invoices', 'container-transactions', 'leads', 'standing-orders'] as $path) {
            $this->get("/admin/{$path}")->assertOk();
        }
    }

    public function test_container_ledger_loads(): void
    {
        $this->get('/admin/container-ledger')->assertOk();
    }

    public function test_order_number_auto_generates(): void
    {
        $order = \App\Models\Order::create([
            'customer_id' => \App\Models\Customer::first()->id,
            'order_date' => now()->toDateString(),
            'status' => 'pending',
            'total' => 5000,
        ]);

        $this->assertNotEmpty($order->order_no);
        $this->assertStringStartsWith('SO-', $order->order_no);
    }

    public function test_lead_pipeline_loads_and_moves(): void
    {
        $this->get('/admin/lead-pipeline')->assertOk();

        $lead = Lead::where('stage', 'new')->firstOrFail();

        Livewire::test(LeadPipeline::class)
            ->assertOk()
            ->call('move', $lead->id, 'forward');

        $this->assertSame('contacted', $lead->refresh()->stage);
    }

    public function test_sales_report_and_csv_export(): void
    {
        $this->get('/admin/sales-report')->assertOk();

        Livewire::test(SalesReport::class)
            ->assertOk()
            ->set('fromDate', now()->subDays(60)->toDateString())
            ->set('toDate', now()->toDateString())
            ->call('exportCsv')
            ->assertFileDownloaded();
    }
}
