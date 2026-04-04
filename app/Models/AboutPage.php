<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AboutPage extends Model
{
    protected $fillable = ['title_fr','title_en','intro_fr','intro_en','story_fr','story_en','philosophy_fr','philosophy_en','qualifications_fr','qualifications_en','image_path','meta_title_fr','meta_title_en','meta_description_fr','meta_description_en','is_published'];
    protected $casts = ['is_published' => 'boolean'];
    public function scopePublished(Builder $q): Builder { return $q->where('is_published', true); }
    public function getLocalizedTitleAttribute(): string { return app()->getLocale() === 'fr' ? $this->title_fr : $this->title_en; }
    public function getLocalizedIntroAttribute(): ?string { return app()->getLocale() === 'fr' ? $this->intro_fr : $this->intro_en; }
    public function getLocalizedStoryAttribute(): ?string { return app()->getLocale() === 'fr' ? $this->story_fr : $this->story_en; }
    public function getLocalizedPhilosophyAttribute(): ?string { return app()->getLocale() === 'fr' ? $this->philosophy_fr : $this->philosophy_en; }
    public function getLocalizedQualificationsAttribute(): ?string { return app()->getLocale() === 'fr' ? $this->qualifications_fr : $this->qualifications_en; }
    public function getImageUrlAttribute(): ?string { return $this->image_path ? Storage::disk('public')->url($this->image_path) : null; }
}
