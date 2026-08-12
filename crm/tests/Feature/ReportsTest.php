<?php

namespace Tests\Feature;

use App\Exports\LeadsExport;
use App\Exports\SalesExport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_reports_page_accessible_by_all_roles(): void
    {
        foreach (['admin', 'sales', 'partner'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $response = $this->actingAs($user)->get(route('reports.index'));

            $response->assertStatus(200);
            $response->assertViewIs('reports.index');
            $response->assertSee('Export Data Leads');
            $response->assertSee('Export Laporan Penjualan');
        }
    }

    public function test_export_leads_queues_csv_with_custom_period(): void
    {
        Excel::fake();

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('export.leads', [
            'period' => 'custom',
            'start' => '2026-01-01',
            'end' => '2026-01-31',
            'format' => 'csv',
        ]));

        $response->assertRedirect();
        Excel::assertQueued('daftar-leads-'.date('Y-m-d').'.csv', function (LeadsExport $export) {
            return $export instanceof LeadsExport;
        });
    }

    public function test_export_sales_queues_excel_with_weekly_period(): void
    {
        Excel::fake();

        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('export.sales', [
            'period' => 'weekly',
            'format' => 'xlsx',
        ]));

        $response->assertRedirect();
        Excel::assertQueued('laporan-penjualan-'.date('Y-m-d').'.xlsx', function (SalesExport $export) {
            return $export instanceof SalesExport;
        });
    }

    public function test_guest_cannot_access_reports_page(): void
    {
        $this->get(route('reports.index'))->assertRedirect(route('login'));
    }
}
