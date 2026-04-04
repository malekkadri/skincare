<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationAiResult extends Model
{
    protected $fillable = [
        'consultation_id',
        'provider',
        'model',
        'summary_text',
        'recommended_services_json',
        'risk_flags_json',
        'raw_response_json',
        'status',
        'error_message',
        'generated_at',
    ];

    protected $casts = [
        'recommended_services_json' => 'array',
        'risk_flags_json' => 'array',
        'raw_response_json' => 'array',
        'generated_at' => 'datetime',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }
}
