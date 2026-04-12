<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\File;
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

        $normalizedPath = ltrim(str_replace('\\', '/', $path), '/');

        // Support legacy values like "public/storage/gallery/file.png".
        if (Str::startsWith($normalizedPath, 'public/storage/')) {
            $normalizedPath = Str::after($normalizedPath, 'public/storage/');
        } elseif (Str::startsWith($normalizedPath, 'storage/')) {
            $normalizedPath = Str::after($normalizedPath, 'storage/');
        } elseif (Str::startsWith($normalizedPath, 'public/')) {
            $normalizedPath = Str::after($normalizedPath, 'public/');
        }

        $normalizedPath = ltrim($normalizedPath, '/');

        // Prefer direct public files first (works when hosts disallow symlinks).
        if (File::exists(public_path($normalizedPath))) {
            return asset($normalizedPath);
        }

        if (File::exists(public_path('storage/'.$normalizedPath))) {
            return asset('storage/'.$normalizedPath);
        }

        // Standard Laravel public disk URL (symlinked /storage path).
        if (Storage::disk('public')->exists($normalizedPath) && File::exists(public_path('storage'))) {
            return Storage::disk('public')->url($normalizedPath);
        }

        $encodedPath = collect(explode('/', $normalizedPath))
            ->filter(static fn (string $segment): bool => $segment !== '')
            ->map(static fn (string $segment): string => rawurlencode($segment))
            ->implode('/');

        return url('/media/'.$encodedPath);
    }
}
