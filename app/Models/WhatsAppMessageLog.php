<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppMessageLog extends Model
{
    protected $table = 'whatsapp_message_logs';

    protected $fillable = [
        'appointment_id',
        'related_consultation_id',
        'customer_id',
        'template_key',
        'language',
        'recipient_phone',
        'message_body',
        'status',
        'scheduled_for',
        'sent_at',
        'failed_at',
        'attempts',
        'next_retry_at',
        'error_code',
        'automation_source',
        'idempotency_key',
        'provider_response',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
        'failed_at' => 'datetime',
        'next_retry_at' => 'datetime',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class, 'related_consultation_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
