<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'name',
        'dob',
        'gender',
        'address',
        'contact',
    ];

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }
}
