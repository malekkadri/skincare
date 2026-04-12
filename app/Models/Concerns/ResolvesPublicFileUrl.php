<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
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

        if (File::exists(public_path('storage'))) {
            return Storage::disk('public')->url($normalizedPath);
        }

        $encodedPath = collect(explode('/', $normalizedPath))
            ->filter(fn (string $segment): bool => $segment !== '')
            ->map(static fn (string $segment): string => rawurlencode($segment))
            ->implode('/');

        return url('/media/'.$encodedPath);
    }
}
