<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class LeadServiceTest extends TestCase
{
    public function test_opportunity_value_formatting(): void
    {
        $value = 15000000;
        $formatted = 'Rp '.number_format($value, 0, ',', '.');

        $this->assertEquals('Rp 15.000.000', $formatted);
    }

    public function test_valid_lead_statuses(): void
    {
        $validStatuses = ['new', 'contacted', 'proposal', 'negotiation', 'won', 'lost'];

        $this->assertContains('proposal', $validStatuses);
        $this->assertNotContains('pending', $validStatuses);
    }
}
