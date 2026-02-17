<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\Usuario::where('correo', 'admin@gmail.com')->first();

if ($user) {
    echo "User found: Yes\n";
    echo "User ID: " . $user->ID_Usuario . "\n";
    echo "Email: " . $user->correo . "\n";
    echo "Role: " . $user->rol . "\n";
    
    // Test password
    $passwordCheck = Illuminate\Support\Facades\Hash::check('admin123', $user->password);
    echo "Password check for 'admin123': " . ($passwordCheck ? 'Valid' : 'Invalid') . "\n";
    
    // Show the raw password hash (first few characters for debugging)
    echo "Password hash starts with: " . substr($user->password, 0, 10) . "...\n";
} else {
    echo "User not found\n";
}