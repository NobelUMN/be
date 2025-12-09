
<?php
use App\Models\User;

// Create or update admin user
$user = User::where('username', 'admin')->first();
if ($user) {
    $user->password = bcrypt('password');
    $user->save();
    echo "Admin password updated\n";
} else {
    User::create([
        'username' => 'admin',
        'password' => bcrypt('password'),
        'role' => 'admin'
    ]);
    echo "Admin user created\n";
}

// Create kasir user
$kasir = User::where('username', 'kasir')->first();
if ($kasir) {
    $kasir->password = bcrypt('password');
    $kasir->save();
    echo "Kasir password updated\n";
} else {
    User::create([
        'username' => 'kasir',
        'password' => bcrypt('password'),
        'role' => 'kasir'
    ]);
    echo "Kasir user created\n";
}

echo "Users ready!\n";
