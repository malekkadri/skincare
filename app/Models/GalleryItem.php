<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    protected $fillable = ['title_fr','title_en','caption_fr','caption_en','image_path','category','is_before_after','is_featured','is_active','sort_order'];
    protected $casts = ['is_before_after' => 'boolean','is_featured' => 'boolean','is_active' => 'boolean'];
    public function scopeActive(Builder $q): Builder { return $q->where('is_active', true); }
    public function scopeFeatured(Builder $q): Builder { return $q->where('is_featured', true); }
    public function scopeOrdered(Builder $q): Builder { return $q->orderBy('sort_order')->orderByDesc('id'); }
    public function getLocalizedTitleAttribute(): ?string { return app()->getLocale() === 'fr' ? $this->title_fr : $this->title_en; }
    public function getLocalizedCaptionAttribute(): ?string { return app()->getLocale() === 'fr' ? $this->caption_fr : $this->caption_en; }
    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::disk('public')->url($this->image_path) : null;
    }
}
