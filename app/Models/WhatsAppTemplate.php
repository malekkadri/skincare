<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WhatsAppTemplate extends Model
{
    public const KEYS = [
        'booking_confirmation',
        'booking_cancellation',
        'booking_rescheduled',
        'appointment_reminder_24h',
        'appointment_reminder_2h',
    ];

    protected $fillable = [
        'key',
        'language',
        'message_body',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
