<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login()
    {
        $response = $this->get('/referrals');
        
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create([
            'role' => 'admin_pusat',
        ]);
        
        $response = $this->actingAs($user)->get('/');
        
        $response->assertStatus(200);
    }
    
    public function test_authenticated_user_can_access_referrals()
    {
        $user = User::factory()->create([
            'role' => 'admin_pusat',
        ]);
        
        $response = $this->actingAs($user)->get('/referrals');
        
        $response->assertStatus(200);
    }
}
