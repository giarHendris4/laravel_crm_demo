<?php

namespace Tests\Unit\Models;

use App\Models\Lead;
use App\Models\LeadCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadCategoryModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_contains_expected_attributes(): void
    {
        $category = new LeadCategory;

        $this->assertContains('name', $category->getFillable());
        $this->assertContains('slug', $category->getFillable());
        $this->assertContains('description', $category->getFillable());
    }

    public function test_category_has_many_leads(): void
    {
        $sales = User::factory()->create(['role' => 'sales']);
        $category = LeadCategory::create(['name' => 'Korporasi', 'slug' => 'korporasi', 'description' => 'test']);

        Lead::factory()->count(2)->create(['user_id' => $sales->id, 'lead_category_id' => $category->id]);

        $this->assertInstanceOf(Lead::class, $category->leads->first());
        $this->assertCount(2, $category->leads);
    }
}
