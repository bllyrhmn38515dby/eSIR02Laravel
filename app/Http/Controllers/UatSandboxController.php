<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UatSandboxController extends Controller
{
    /**
     * Show the Interactive UAT Sandbox page.
     */
    public function index()
    {
        return view('uat-sandbox.index');
    }

    /**
     * Mock a login request for UAT Sandbox.
     */
    public function mockLogin(Request $request)
    {
        // Simulate processing time
        sleep(1);

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        // Check if user exists and password is correct
        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah.'
            ], 401);
        }

        // Check if the requested role matches the user's actual role in the database
        if ($user->role !== $request->role) {
            return response()->json([
                'success' => false,
                'message' => "Role tidak valid! User ini memiliki role '{$user->role}', bukan '{$request->role}'."
            ], 403);
        }

        // Generate a mock JWT token if valid
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode([
            'role' => $user->role,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + 3600
        ]));
        $signature = base64_encode('mock-signature-do-not-use');
        
        $token = "$header.$payload.$signature";

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil disimulasikan',
            'token' => $token,
            'role' => $user->role
        ]);
    }
}
