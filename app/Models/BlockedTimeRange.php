<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedTimeRange extends Model
{
    protected $fillable = ['blocked_date', 'start_time', 'end_time', 'reason'];

    protected $casts = ['blocked_date' => 'date'];
}
