<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_NO_SHOW = 'no_show';

    public const ACTIVE_STATUSES = [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_COMPLETED];

    protected $fillable = [
        'customer_id',
        'service_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'booked_currency',
        'booked_price',
        'service_name_snapshot_fr',
        'service_name_snapshot_en',
        'service_price_tnd_snapshot',
        'service_price_eur_snapshot',
        'notes',
        'admin_notes',
        'cancelled_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'cancelled_at' => 'datetime',
        'booked_price' => 'decimal:2',
        'service_price_tnd_snapshot' => 'decimal:2',
        'service_price_eur_snapshot' => 'decimal:2',
    ];

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
            self::STATUS_NO_SHOW,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function scopeOnDate(Builder $query, string $date): Builder
    {
        return $query->whereDate('appointment_date', $date);
    }

    public function scopeActiveForAvailability(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }

    public function getDisplayPriceAttribute(): string
    {
        return number_format((float) $this->booked_price, 2).' '.strtoupper($this->booked_currency);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_CONFIRMED => 'status-confirmed',
            self::STATUS_COMPLETED => 'status-completed',
            self::STATUS_CANCELLED => 'status-cancelled',
            self::STATUS_NO_SHOW => 'status-no-show',
            default => 'status-pending',
        };
    }
}
