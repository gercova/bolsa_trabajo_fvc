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
            'country_code'  => '+51',
            'phone'         => '987654321',
            'nationality'   => 'Peruana',
            'ubigeo'        => '150101',
            'address'       => 'Av. Principal 123, Lima',
            'profession'    => 'Administrador',
            'role'          => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
