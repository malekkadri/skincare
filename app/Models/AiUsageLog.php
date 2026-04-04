<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUsageLog extends Model
{
    protected $fillable = [
        'feature_key',
        'provider',
        'model',
        'input_context_summary',
        'output_summary',
        'status',
        'error_message',
        'related_consultation_id',
        'admin_user_id',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class, 'related_consultation_id');
    }

    public function adminUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }
}
