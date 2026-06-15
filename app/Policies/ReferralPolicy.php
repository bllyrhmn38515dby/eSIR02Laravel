<?php

namespace App\Policies;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReferralPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin_pusat', 'admin_faskes', 'driver']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Referral $referral): bool
    {
        // Admin pusat bisa lihat semua
        if ($user->role === 'admin_pusat') {
            return true;
        }

        // Admin faskes hanya bisa lihat jika rujukan berasal dari atau menuju faskesnya
        if ($user->role === 'admin_faskes') {
            return $user->faskes_id === $referral->from_faskes_id || $user->faskes_id === $referral->to_faskes_id;
        }

        // Driver hanya bisa lihat jika ditugaskan
        if ($user->role === 'driver') {
            return $user->id === $referral->driver_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin_faskes';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Referral $referral): bool
    {
        // Admin faskes pengirim bisa edit jika masih draft/sent
        if ($user->role === 'admin_faskes' && $user->faskes_id === $referral->from_faskes_id) {
            return in_array($referral->status, ['draft', 'sent']);
        }

        // Admin faskes penerima bisa terima (update status ke accepted dll)
        if ($user->role === 'admin_faskes' && $user->faskes_id === $referral->to_faskes_id) {
            return in_array($referral->status, ['sent', 'accepted', 'traveling', 'arrived']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Referral $referral): bool
    {
        // Hanya admin faskes pembuat dan hanya jika draft
        return $user->role === 'admin_faskes' && $user->faskes_id === $referral->from_faskes_id && $referral->status === 'draft';
    }
}
