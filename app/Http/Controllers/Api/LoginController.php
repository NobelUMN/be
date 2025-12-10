<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Cek apakah sudah ada session login
        if (Auth::check()) {
            $currentUser = Auth::user();
            return response()->json([
                'success' => false,
                'message' => 'Sudah ada user yang login: ' . $currentUser->username,
                'current_user' => [
                    'username' => $currentUser->username,
                    'role' => $currentUser->role
                ]
            ], 403);
        }

        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Username tidak ditemukan'
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah'
            ], 401);
        }

        // Login pakai session (stateful) dengan remember
        Auth::login($user, true);

        // Regenerate session untuk keamanan
        $request->session()->regenerate();

        // Generate token untuk frontend (optional, bisa tetap pakai atau tidak)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'role' => $user->role,
                'token' => $token
            ]
        ])->cookie(
            'laravel_session',
            $request->session()->getId(),
            config('session.lifetime'),
            '/',
            config('session.domain'),
            config('session.secure'),
            config('session.http_only'),
            false,
            config('session.same_site')
        );
    }

    public function logout(Request $request)
    {
        // Logout session
        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Hapus token juga (jika ada)
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ])->cookie(
            'laravel_session',
            '',
            -1,
            '/',
            config('session.domain'),
            config('session.secure'),
            config('session.http_only'),
            false,
            config('session.same_site')
        );
    }
}
