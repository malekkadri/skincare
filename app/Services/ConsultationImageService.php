<?php

namespace App\Services;

use App\Models\Consultation;
use Illuminate\Http\UploadedFile;

class ConsultationImageService
{
    public function storeUploadedImages(Consultation $consultation, array $files, string $context = 'consultation'): void
    {
        foreach (array_values($files) as $index => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $path = $file->store('consultation-images/'.$consultation->id, 'local');

            $consultation->images()->create([
                'context' => $context,
                'disk' => 'local',
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size_bytes' => $file->getSize() ?: 0,
                'sort_order' => $index,
            ]);
        }
    }
}
