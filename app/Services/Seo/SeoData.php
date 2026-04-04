<?php

namespace App\Services\Seo;

class SeoData
{
    public function __construct(
        public string $title,
        public string $description,
        public string $canonical,
        public string $ogType = 'website',
        public ?string $image = null,
    ) {
    }
}
