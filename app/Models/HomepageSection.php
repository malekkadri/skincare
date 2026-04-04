<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomepageSection extends Model
{
    protected $fillable = ['key','title_fr','title_en','content_fr','content_en','button_text_fr','button_text_en','button_url','secondary_button_text_fr','secondary_button_text_en','secondary_button_url','image_path','is_active','sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive(Builder $query): Builder { return $query->where('is_active', true); }
    public function scopeOrdered(Builder $query): Builder { return $query->orderBy('sort_order')->orderBy('id'); }
    public function getLocalizedTitleAttribute(): ?string { return app()->getLocale() === 'fr' ? $this->title_fr : $this->title_en; }
    public function getLocalizedContentAttribute(): ?string { return app()->getLocale() === 'fr' ? $this->content_fr : $this->content_en; }
    public function getLocalizedButtonTextAttribute(): ?string { return app()->getLocale() === 'fr' ? $this->button_text_fr : $this->button_text_en; }
    public function getLocalizedSecondaryButtonTextAttribute(): ?string { return app()->getLocale() === 'fr' ? $this->secondary_button_text_fr : $this->secondary_button_text_en; }
    public function getImageUrlAttribute(): ?string { return $this->image_path ? Storage::disk('public')->url($this->image_path) : null; }
}
