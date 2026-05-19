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

        if (Str::startsWith($normalizedPath, 'storage/app/public/')) {
            $normalizedPath = Str::after($normalizedPath, 'storage/app/public/');
        } elseif (Str::startsWith($normalizedPath, 'public/storage/')) {
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

        if (Storage::disk('public')->exists($normalizedPath)) {
            $encodedPath = collect(explode('/', $normalizedPath))
                ->filter(static fn (string $segment): bool => $segment !== '')
                ->map(static fn (string $segment): string => rawurlencode($segment))
                ->implode('/');

            return route('media.public', ['path' => $encodedPath]);
        }

        return null;
    }
}
