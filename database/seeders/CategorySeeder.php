<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Frutas',
                'description' => 'Frutas frescas da agricultura familiar',
                'active' => true
            ],
            [
                'name' => 'Verduras',
                'description' => 'Verduras orgânicas e frescas',
                'active' => true
            ],
            [
                'name' => 'Legumes',
                'description' => 'Legumes frescos e nutritivos',
                'active' => true
            ],
            [
                'name' => 'Grãos e Cereais',
                'description' => 'Grãos e cereais naturais',
                'active' => true
            ],
            [
                'name' => 'Laticínios',
                'description' => 'Produtos lácteos artesanais',
                'active' => true
            ],
            [
                'name' => 'Carnes',
                'description' => 'Carnes frescas de criação familiar',
                'active' => true
            ],
            [
                'name' => 'Ovos',
                'description' => 'Ovos frescos de galinhas caipiras',
                'active' => true
            ],
            [
                'name' => 'Mel e Derivados',
                'description' => 'Mel puro e produtos apícolas',
                'active' => true
            ],
            [
                'name' => 'Temperos e Ervas',
                'description' => 'Temperos naturais e ervas aromáticas',
                'active' => true
            ],
            [
                'name' => 'Produtos Processados',
                'description' => 'Conservas, geleias e produtos artesanais',
                'active' => true
            ]
        ];

        foreach ($categories as $categoryData) {
            $categoryData['slug'] = Str::slug($categoryData['name']);
            Category::create($categoryData);
        }
    }
}
