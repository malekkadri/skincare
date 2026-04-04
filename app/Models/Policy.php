<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = ['title_fr','title_en','slug','content_fr','content_en','meta_title_fr','meta_title_en','meta_description_fr','meta_description_en','is_active','sort_order'];
    protected $casts = ['is_active' => 'boolean'];
    public function scopeActive(Builder $q): Builder { return $q->where('is_active', true); }
    public function scopeOrdered(Builder $q): Builder { return $q->orderBy('sort_order')->orderBy('id'); }
    public function getRouteKeyName(): string { return 'slug'; }
    public function getLocalizedTitleAttribute(): string { return app()->getLocale() === 'fr' ? $this->title_fr : $this->title_en; }
    public function getLocalizedContentAttribute(): string { return app()->getLocale() === 'fr' ? $this->content_fr : $this->content_en; }
}
