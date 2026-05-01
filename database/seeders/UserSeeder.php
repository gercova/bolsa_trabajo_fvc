<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'dni'           => '12345678',
            'names'         => 'Administrador del Sistema',
            'email'         => 'admin@example.com',
            'password'      => Hash::make('password'),
            'job_position'  => 'Administrador',
            'role'          => 'Admin',
            'email_verified_at' => now(),
        ]);
    }
}
