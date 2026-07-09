<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('referral.{id}', function ($user, $id) {
    $referral = \App\Models\Referral::find($id);
    
    if (!$referral) return false;

    // Admin Pusat bisa akses semua
    if ($user->role === 'admin_pusat') {
        return ['id' => $user->id, 'name' => $user->name, 'role' => $user->role];
    }

    // Driver yang bertugas
    if ($user->role === 'driver' && $referral->driver_id === $user->id) {
        return ['id' => $user->id, 'name' => $user->name, 'role' => $user->role];
    }

    // Admin Faskes asal atau tujuan
    if ($user->role === 'admin_faskes' && ($user->faskes_id === $referral->from_faskes_id || $user->faskes_id === $referral->to_faskes_id)) {
        return ['id' => $user->id, 'name' => $user->name, 'role' => $user->role];
    }

    return false;
});
