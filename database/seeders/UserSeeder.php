<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo tài khoản Admin
        User::create([
            'name'      => 'Admin',
            'email'     => 'admin@dongho.com',
            'password'  => Hash::make('123456'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // Tạo tài khoản User test
        User::create([
            'name'      => 'Nguyễn Văn A',
            'email'     => 'user@dongho.com',
            'password'  => Hash::make('123456'),
            'role'      => 'user',
            'phone'     => '0901234567',
            'is_active' => true,
        ]);
    }
}