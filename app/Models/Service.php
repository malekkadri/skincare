<?php

namespace App\Models;

use App\Models\Concerns\ResolvesPublicFileUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use ResolvesPublicFileUrl;

    protected $fillable = [
        'category_id',
        'name_fr',
        'name_en',
        'slug',
        'short_description_fr',
        'short_description_en',
        'description_fr',
        'description_en',
        'meta_title_fr',
        'meta_title_en',
        'meta_description_fr',
        'meta_description_en',
        'price_tnd',
        'price_eur',
        'duration_minutes',
        'buffer_minutes',
        'image_path',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'price_tnd' => 'decimal:2',
        'price_eur' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name_en');
    }

    public function getLocalizedNameAttribute(): string
    {
        return app()->getLocale() === 'fr' ? $this->name_fr : $this->name_en;
    }

    public function getLocalizedShortDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->short_description_fr : $this->short_description_en;
    }

    public function getLocalizedDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'fr' ? $this->description_fr : $this->description_en;
    }

    public function getDisplayPriceAttribute(): string
    {
        $currency = strtoupper((string) session('currency', 'TND'));

        if ((float) $this->price_tnd <= 0.0) {
            return app()->getLocale() === 'fr' ? 'Tarif sur consultation' : 'Price on consultation';
        }

        if ($currency === 'EUR') {
            return number_format((float) $this->price_eur, 2).' EUR';
        }

        return number_format((float) $this->price_tnd, 2).' TND';
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->resolvePublicFileUrl($this->image_path);
    }
}
