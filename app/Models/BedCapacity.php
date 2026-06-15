<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedCapacity extends Model
{
    use HasFactory;

    protected $fillable = [
        'faskes_id',
        'room_name',
        'capacity',
        'available',
        'last_updated',
    ];

    public function faskes()
    {
        return $this->belongsTo(Faskes::class);
    }
}
