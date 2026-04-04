<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Consultation extends Model
{
    public const STATUSES = ['new', 'reviewed', 'contacted', 'converted', 'archived'];

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'preferred_language',
        'age_range',
        'skin_type',
        'skin_sensitivity_level',
        'main_concerns',
        'allergies',
        'current_products',
        'current_treatments_or_medications',
        'pregnancy_or_breastfeeding_status',
        'preferred_goals',
        'additional_notes',
        'consent',
        'status',
        'admin_notes',
        'customer_id',
    ];

    protected $casts = [
        'consent' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


    public function images(): HasMany
    {
        return $this->hasMany(ConsultationImage::class)->orderBy('sort_order');
    }

    public function aiResults(): HasMany
    {
        return $this->hasMany(ConsultationAiResult::class);
    }

    public function latestAiResult(): HasOne
    {
        return $this->hasOne(ConsultationAiResult::class)->latestOfMany('generated_at');
    }

    public function whatsappLogs(): HasMany
    {
        return $this->hasMany(WhatsAppMessageLog::class, 'related_consultation_id')->latest();
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.($this->last_name ?? ''));
    }
}
