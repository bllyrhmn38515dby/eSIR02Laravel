<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambulance extends Model
{
    use HasFactory;

    protected $fillable = [
        'faskes_id',
        'police_number',
        'vehicle_type',
        'status',
    ];

    public function faskes()
    {
        return $this->belongsTo(Faskes::class);
    }
}
