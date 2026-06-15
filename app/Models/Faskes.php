<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faskes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'address',
        'latitude',
        'longitude',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function bedCapacities()
    {
        return $this->hasMany(BedCapacity::class);
    }

    public function referralsFrom()
    {
        return $this->hasMany(Referral::class, 'from_faskes_id');
    }

    public function referralsTo()
    {
        return $this->hasMany(Referral::class, 'to_faskes_id');
    }
}
