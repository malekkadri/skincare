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
        'user_summary',
        'admin_summary',
        'recommended_services_json',
        'risk_flags_json',
        'raw_response_json',
        'normalized_result_json',
        'status',
        'confidence_score',
        'needs_human_review',
        'refer_to_dermatologist',
        'error_message',
        'generated_at',
        'processed_at',
    ];

    protected $casts = [
        'recommended_services_json' => 'array',
        'risk_flags_json' => 'array',
        'raw_response_json' => 'array',
        'normalized_result_json' => 'array',
        'needs_human_review' => 'boolean',
        'refer_to_dermatologist' => 'boolean',
        'generated_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }
}
