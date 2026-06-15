<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_number',
        'patient_id',
        'from_faskes_id',
        'to_faskes_id',
        'created_by',
        'driver_id',
        'ambulance_id',
        'diagnosis',
        'blood_pressure',
        'heart_rate',
        'respiratory_rate',
        'temperature',
        'gcs_score',
        'reason',
        'bed_capacity_id',
        'response_time_minutes',
        'status',
        'notes',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class)->latest();
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function fromFaskes()
    {
        return $this->belongsTo(Faskes::class, 'from_faskes_id');
    }

    public function toFaskes()
    {
        return $this->belongsTo(Faskes::class, 'to_faskes_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function ambulance()
    {
        return $this->belongsTo(Ambulance::class);
    }

    public function documents()
    {
        return $this->hasMany(ReferralDocument::class);
    }

    public function trackingPoints()
    {
        return $this->hasMany(TrackingPoint::class);
    }

    /**
     * Get Triage Color and Label based on Clinical Data
     */
    public function getTriageInfo()
    {
        $gcs = $this->gcs_score;
        
        if (!$gcs) {
            return ['color' => 'secondary', 'label' => 'Non-Emergency', 'class' => 'bg-secondary'];
        }

        if ($gcs <= 8) {
            return ['color' => 'danger', 'label' => 'P1 - EMERGENCY (RED)', 'class' => 'bg-danger'];
        } elseif ($gcs <= 12) {
            return ['color' => 'warning', 'label' => 'P2 - URGENT (YELLOW)', 'class' => 'bg-warning text-dark'];
        } else {
            return ['color' => 'success', 'label' => 'P3 - STABLE (GREEN)', 'class' => 'bg-success'];
        }
    }
}
