<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 1 akun admin default
        User::create([
            'name' => 'Admin KSOP',
            'email' => 'admin@ksop.go.id',
            'password' => Hash::make('admin123'), 
        ]);
    }
}