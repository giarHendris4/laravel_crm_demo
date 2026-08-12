<?php

namespace Tests\Unit\Helpers;

use App\Helpers\FormatHelper;
use PHPUnit\Framework\TestCase;

class FormatHelperTest extends TestCase
{
    public function test_rupiah_formats_in_indonesian_currency(): void
    {
        $this->assertSame('Rp 15.000.000', FormatHelper::rupiah(15000000));
        $this->assertSame('Rp 1.000', FormatHelper::rupiah(1000));
    }

    public function test_rupiah_handles_decimal_and_null(): void
    {
        $this->assertSame('Rp 0', FormatHelper::rupiah(null));
        $this->assertSame('Rp 100.000', FormatHelper::rupiah(100000.25));
    }

    public function test_status_label_transforms_snake_case(): void
    {
        $this->assertSame('CLOSED WON', FormatHelper::statusLabel('closed_won'));
        $this->assertSame('NEW', FormatHelper::statusLabel('new'));
        $this->assertSame('-', FormatHelper::statusLabel(null));
        $this->assertSame('-', FormatHelper::statusLabel(''));
    }

    public function test_status_lists_are_valid(): void
    {
        $this->assertContains('proposal', FormatHelper::leadStatuses());
        $this->assertNotContains('pending', FormatHelper::leadStatuses());
        $this->assertContains('closed_won', FormatHelper::dealStages());
        $this->assertContains('active', FormatHelper::customerStatuses());
    }

    public function test_global_helper_functions(): void
    {
        $this->assertSame('Rp 5.000', format_rupiah(5000));
        $this->assertSame('CLOSED LOST', status_label('closed_lost'));
    }
}
