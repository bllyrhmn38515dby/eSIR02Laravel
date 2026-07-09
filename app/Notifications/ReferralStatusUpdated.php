<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReferralStatusUpdated extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $referral;

    public function __construct($referral)
    {
        $this->referral = $referral;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'referral_id' => $this->referral->id,
            'referral_number' => $this->referral->referral_number,
            'status' => $this->referral->status,
            'message' => "Rujukan {$this->referral->referral_number} statusnya menjadi: " . strtoupper($this->referral->status),
            'url' => route('referrals.edit', $this->referral->id),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => "🚨 Peringatan Sistem Rujukan",
            'body' => "Rujukan {$this->referral->referral_number} telah berubah status menjadi " . strtoupper($this->referral->status),
            'url' => route('referrals.edit', $this->referral->id)
        ]);
    }
}
