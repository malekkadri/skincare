<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['question_fr','question_en','answer_fr','answer_en','category','is_active','sort_order'];
    protected $casts = ['is_active' => 'boolean'];
    public function scopeActive(Builder $q): Builder { return $q->where('is_active', true); }
    public function scopeOrdered(Builder $q): Builder { return $q->orderBy('sort_order')->orderBy('id'); }
    public function getLocalizedQuestionAttribute(): string { return app()->getLocale() === 'fr' ? $this->question_fr : $this->question_en; }
    public function getLocalizedAnswerAttribute(): string { return app()->getLocale() === 'fr' ? $this->answer_fr : $this->answer_en; }
}
