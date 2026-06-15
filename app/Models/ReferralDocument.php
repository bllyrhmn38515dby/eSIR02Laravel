<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_id',
        'file_path',
        'document_type',
        'uploaded_by',
    ];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
