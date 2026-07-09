<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_id',
        'latitude',
        'longitude',
        'heading',
        'recorded_at',
    ];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }
}
