<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaunchReadinessPublicGuardsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_service_page_rejects_inactive_service(): void
    {
        Setting::current();
        $category = ServiceCategory::query()->create([
            'name_fr' => 'Cat FR',
            'name_en' => 'Cat EN',
            'slug' => 'cat-en',
            'is_active' => true,
        ]);

        $service = Service::query()->create([
            'category_id' => $category->id,
            'name_fr' => 'Soin FR',
            'name_en' => 'Service EN',
            'slug' => 'service-en',
            'price_tnd' => 120,
            'price_eur' => 35,
            'duration_minutes' => 45,
            'is_active' => false,
        ]);

        $this->get(route('services.show', $service->slug))->assertNotFound();
    }

    public function test_available_slots_endpoint_rejects_inactive_service(): void
    {
        Setting::current();
        $category = ServiceCategory::query()->create([
            'name_fr' => 'Cat FR',
            'name_en' => 'Cat EN',
            'slug' => 'cat-en',
            'is_active' => true,
        ]);

        $service = Service::query()->create([
            'category_id' => $category->id,
            'name_fr' => 'Soin FR',
            'name_en' => 'Service EN',
            'slug' => 'service-en',
            'price_tnd' => 120,
            'price_eur' => 35,
            'duration_minutes' => 45,
            'is_active' => false,
        ]);

        $this->getJson(route('booking.available-slots', [
            'service_id' => $service->id,
            'date' => now()->toDateString(),
        ]))->assertStatus(422)->assertJsonStructure(['message', 'slots']);
    }
}
