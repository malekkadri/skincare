<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Testimonial extends Model
{
    protected $fillable = ['client_name','title_fr','title_en','content_fr','content_en','rating','service_id','is_featured','is_active','sort_order'];
    protected $casts = ['is_featured' => 'boolean', 'is_active' => 'boolean'];
    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
    public function scopeActive(Builder $q): Builder { return $q->where('is_active', true); }
    public function scopeFeatured(Builder $q): Builder { return $q->where('is_featured', true); }
    public function scopeOrdered(Builder $q): Builder { return $q->orderBy('sort_order')->orderByDesc('id'); }
    public function getLocalizedTitleAttribute(): ?string { return app()->getLocale() === 'fr' ? $this->title_fr : $this->title_en; }
    public function getLocalizedContentAttribute(): string { return app()->getLocale() === 'fr' ? $this->content_fr : $this->content_en; }
}
