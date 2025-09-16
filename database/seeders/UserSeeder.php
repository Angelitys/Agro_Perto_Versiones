<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'João Silva',
            'email' => 'joao@fazenda.com',
            'password' => Hash::make('password123'),
            'type' => 'producer',
        ]);

        User::create([
            'name' => 'Maria Consumidora',
            'email' => 'maria@consumidora.com',
            'password' => Hash::make('password123'),
            'type' => 'consumer',
        ]);
    }
}
