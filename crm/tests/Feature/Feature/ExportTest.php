<?php

namespace Tests\Feature;

use App\Exports\LeadsExport;
use App\Exports\SalesExport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function test_admin_dapat_menjadwalkan_export_data_leads()
    {
        Excel::fake();

        $response = $this->actingAs($this->admin)->get(route('export.leads'));

        $response->assertRedirect();
        Excel::assertQueued('daftar-leads-'.date('Y-m-d').'.xlsx', function (LeadsExport $export) {
            return $export instanceof LeadsExport;
        });
    }

    #[Test]
    public function test_admin_dapat_menjadwalkan_export_laporan_sales()
    {
        Excel::fake();

        $response = $this->actingAs($this->admin)->get(route('export.sales'));

        $response->assertRedirect();
        Excel::assertQueued('laporan-penjualan-'.date('Y-m-d').'.xlsx', function (SalesExport $export) {
            return $export instanceof SalesExport;
        });
    }
}
