<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'preferred_language',
        'preferred_currency',
        'notes',
        'allergies',
        'skin_notes',
        'medical_notes',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }


    public function progressPhotos(): HasMany
    {
        return $this->hasMany(CustomerProgressPhoto::class)->latest('captured_on')->latest();
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.($this->last_name ?? ''));
    }
}
