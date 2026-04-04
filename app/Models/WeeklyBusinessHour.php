<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WeeklyBusinessHour extends Model
{
    protected $fillable = ['day_of_week', 'is_open', 'start_time', 'end_time'];

    protected $casts = ['is_open' => 'boolean'];

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('is_open', true);
    }
}
