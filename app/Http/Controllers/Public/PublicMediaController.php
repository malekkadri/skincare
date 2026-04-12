<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PublicMediaController extends Controller
{
    public function show(string $path): Response
    {
        $normalizedPath = trim(str_replace('\\', '/', $path), '/');

        abort_if($normalizedPath === '' || str_contains($normalizedPath, '..'), 404);

        $disk = Storage::disk('public');
        abort_unless($disk->exists($normalizedPath), 404);

        $mimeType = $disk->mimeType($normalizedPath) ?: 'application/octet-stream';

        return response($disk->get($normalizedPath), 200, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
