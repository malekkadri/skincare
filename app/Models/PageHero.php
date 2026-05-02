<?php

namespace App\Models;

use App\Models\Concerns\ResolvesPublicFileUrl;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PageHero extends Model
{
    use ResolvesPublicFileUrl;

    protected $fillable = ['page_key','title_fr','title_en','subtitle_fr','subtitle_en','description_fr','description_en','image_path','mobile_image_path','alt_text_fr','alt_text_en','cta_label_fr','cta_label_en','cta_url','is_active','sort_order','overlay_opacity'];
    protected $casts = ['is_active' => 'boolean', 'overlay_opacity' => 'float'];

    public function scopeActive(Builder $query): Builder { return $query->where('is_active', true); }
    public function scopeOrdered(Builder $query): Builder { return $query->orderBy('sort_order')->orderByDesc('id'); }
    public function getLocalizedTitleAttribute(): ?string { return app()->getLocale() === 'fr' ? ($this->title_fr ?: $this->title_en) : ($this->title_en ?: $this->title_fr); }
    public function getLocalizedSubtitleAttribute(): ?string { return app()->getLocale() === 'fr' ? ($this->subtitle_fr ?: $this->subtitle_en) : ($this->subtitle_en ?: $this->subtitle_fr); }
    public function getLocalizedDescriptionAttribute(): ?string { return app()->getLocale() === 'fr' ? ($this->description_fr ?: $this->description_en) : ($this->description_en ?: $this->description_fr); }
    public function getLocalizedAltTextAttribute(): ?string { return app()->getLocale() === 'fr' ? ($this->alt_text_fr ?: $this->alt_text_en) : ($this->alt_text_en ?: $this->alt_text_fr); }
    public function getLocalizedCtaLabelAttribute(): ?string { return app()->getLocale() === 'fr' ? ($this->cta_label_fr ?: $this->cta_label_en) : ($this->cta_label_en ?: $this->cta_label_fr); }
    public function getImageUrlAttribute(): ?string { return $this->resolvePublicFileUrl($this->image_path); }
    public function getMobileImageUrlAttribute(): ?string { return $this->resolvePublicFileUrl($this->mobile_image_path); }
}
