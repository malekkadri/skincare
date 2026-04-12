<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ResolvesPublicFileUrl
{
    protected function resolvePublicFileUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//', 'data:'])) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');
        $normalizedPath = preg_replace('#^(public|storage)/#', '', $normalizedPath) ?: $normalizedPath;

        return Storage::disk('public')->url($normalizedPath);
    }
}
