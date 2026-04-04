<?php

namespace App\Services;

use App\Models\Consultation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConsultationImageService
{
    public function storeUploadedImages(Consultation $consultation, array $files, string $context = 'consultation'): void
    {
        foreach (array_values($files) as $index => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $detectedMime = (string) $file->getMimeType();
            if (! in_array($detectedMime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
                continue;
            }

            $disk = 'local';
            $directory = 'consultation-images/'.$consultation->id;
            $filename = Str::ulid()->toBase32().'.'.$this->extensionForMime($detectedMime);
            $path = $directory.'/'.$filename;

            $binary = $this->stripExifWhenPossible($file, $detectedMime)
                ?? file_get_contents($file->getRealPath());

            if (! is_string($binary) || $binary === '') {
                continue;
            }

            Storage::disk($disk)->put($path, $binary, ['visibility' => 'private']);

            $consultation->images()->create([
                'context' => $context,
                'disk' => $disk,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $detectedMime,
                'size_bytes' => strlen($binary),
                'sort_order' => $index,
            ]);
        }
    }

    private function extensionForMime(string $mime): string
    {
        return match ($mime) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };
    }

    private function stripExifWhenPossible(UploadedFile $file, string $mime): ?string
    {
        if (! function_exists('imagecreatefromstring')) {
            return null;
        }

        $contents = file_get_contents($file->getRealPath());
        if (! is_string($contents) || $contents === '') {
            return null;
        }

        $resource = @imagecreatefromstring($contents);
        if (! $resource) {
            return null;
        }

        ob_start();
        $written = match ($mime) {
            'image/png' => imagepng($resource, null, 6),
            'image/webp' => function_exists('imagewebp') ? imagewebp($resource, null, 85) : false,
            default => imagejpeg($resource, null, 88),
        };

        $data = $written ? ob_get_contents() : false;
        ob_end_clean();
        imagedestroy($resource);

        return is_string($data) && $data !== '' ? $data : null;
    }
}
