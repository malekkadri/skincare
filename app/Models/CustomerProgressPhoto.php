<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerProgressPhoto extends Model
{
    protected $fillable = [
        'customer_id',
        'captured_on',
        'title',
        'notes',
        'disk',
        'path',
        'mime_type',
        'size_bytes',
        'original_name',
    ];

    protected $casts = [
        'captured_on' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
