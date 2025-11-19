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
        // ID 1
        User::create([
            'name' => 'João Silva',
            'email' => 'joao@fazenda.com',
            'password' => Hash::make('password123'),
            'type' => 'producer',
        ]);

        // ID 2
        User::create([
            'name' => 'Maria Consumidora',
            'email' => 'maria@consumidora.com',
            'password' => Hash::make('password123'),
            'type' => 'consumer',
        ]);

        // ID 3
        User::create([
            'name' => 'Pedro Produtor',
            'email' => 'pedro@fazenda.com',
            'password' => Hash::make('password123'),
            'type' => 'producer',
        ]);

        // ID 4 (CORREÇÃO FINAL)
        User::create([
            'name' => 'Ana Consumidora',
            'email' => 'ana@consumidora.com',
            'password' => Hash::make('password123'),
            'type' => 'consumer',
        ]);
    }
}
