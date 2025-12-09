<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-passwords', function () {
    $users = [
        ['username' => 'admin', 'password' => 'admin123', 'role' => 'admin'],
        ['username' => 'kasir', 'password' => 'kasir123', 'role' => 'kasir'],
    ];

    $results = [];
    foreach ($users as $userData) {
        $user = User::where('username', $userData['username'])->first();
        
        if ($user) {
            $user->password = Hash::make($userData['password']);
            $user->save();
            $results[] = "✅ Updated: {$userData['username']}";
        } else {
            User::create([
                'username' => $userData['username'],
                'password' => Hash::make($userData['password']),
                'role' => $userData['role'],
            ]);
            $results[] = "✅ Created: {$userData['username']}";
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'Passwords reset successfully!',
        'results' => $results,
        'credentials' => [
            'admin' => 'admin / admin123',
            'kasir' => 'kasir / kasir123',
        ]
    ]);
});
