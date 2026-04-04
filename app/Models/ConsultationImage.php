<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationImage extends Model
{
    protected $fillable = [
        'consultation_id',
        'context',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size_bytes',
        'sort_order',
    ];

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }
}
