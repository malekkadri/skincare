<?php

namespace Tests\Feature;

use App\Jobs\AnalyzeConsultationFaceImagesJob;
use App\Models\Consultation;
use App\Models\ConsultationAiResult;
use App\Models\ConsultationImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminConsultationAiSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_consultation_image_requires_manage_consultations_permission(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('consultation-images/1/test.jpg', 'jpg');

        $consultation = Consultation::query()->create([
            'first_name' => 'Jane',
            'phone' => '+1000000',
            'preferred_language' => 'en',
            'main_concerns' => 'Texture',
            'consent' => true,
            'status' => 'new',
        ]);

        $image = ConsultationImage::query()->create([
            'consultation_id' => $consultation->id,
            'disk' => 'local',
            'path' => 'consultation-images/1/test.jpg',
            'mime_type' => 'image/jpeg',
        ]);

        $user = User::factory()->create([
            'is_admin' => true,
            'is_active' => true,
            'role' => 'editor',
        ]);

        $this->actingAs($user)
            ->get(route('admin.consultations.image', $image))
            ->assertForbidden();
    }

    public function test_retry_analysis_does_not_dispatch_when_already_pending(): void
    {
        Queue::fake();

        $consultation = Consultation::query()->create([
            'first_name' => 'Jane',
            'phone' => '+1000000',
            'preferred_language' => 'en',
            'main_concerns' => 'Texture',
            'consent' => true,
            'status' => 'new',
        ]);

        ConsultationImage::query()->create([
            'consultation_id' => $consultation->id,
            'disk' => 'local',
            'path' => 'consultation-images/1/test.jpg',
            'mime_type' => 'image/jpeg',
        ]);

        ConsultationAiResult::query()->create([
            'consultation_id' => $consultation->id,
            'status' => 'pending',
            'generated_at' => now(),
        ]);

        $admin = User::factory()->create([
            'is_admin' => true,
            'is_active' => true,
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.consultations.retry-analysis', $consultation))
            ->assertSessionHas('success');

        Queue::assertNotPushed(AnalyzeConsultationFaceImagesJob::class);
    }

    public function test_face_image_upload_validation_rejects_tiny_dimensions(): void
    {
        $payload = [
            'preferred_language' => 'en',
            'main_concerns' => 'Dryness',
            'face_images' => [UploadedFile::fake()->image('tiny.jpg', 120, 120)],
        ];

        $this->post(route('recommender.recommend'), $payload)
            ->assertSessionHasErrors(['face_images.0']);
    }
}
