<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Tomate Cereja',
                'slug' => 'tomate-cereja',
                'description' => 'Tomates cereja frescos e saborosos, cultivados sem agrotóxicos',
                'price' => 8.50,
                'stock_quantity' => 50,
                'unit' => 'kg',
                'category_id' => 2, // Legumes
                'user_id' => 1, // João Silva (produtor)
                'active' => true,
                'origin' => 'Quinta do João',
                'harvest_date' => Carbon::now()->subDays(2),
                'available_from' => Carbon::now()->startOfDay(),
                'available_until' => Carbon::now()->endOfDay(),
                'fair_location' => 'Feira de Santarém',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Alface Americana',
                'slug' => 'alface-americana',
                'description' => 'Alface americana crocante e fresca, ideal para saladas',
                'price' => 2.50,
                'stock_quantity' => 30,
                'unit' => 'unidade',
                'category_id' => 3, // Verduras
                'user_id' => 1, // João Silva
                'active' => true,
                'origin' => 'Quinta do João',
                'harvest_date' => Carbon::now()->subDays(1),
                'available_from' => Carbon::now()->startOfDay(),
                'available_until' => Carbon::now()->endOfDay(),
                'fair_location' => 'Feira de Santarém',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Maçã Gala',
                'slug' => 'maca-gala',
                'description' => 'Maçãs gala doces e suculentas, colhidas na época ideal',
                'price' => 3.20,
                'stock_quantity' => 100,
                'unit' => 'kg',
                'category_id' => 1, // Frutas
                'user_id' => 3, // Pedro Oliveira
                'active' => true,
                'origin' => 'Pomar do Pedro',
                'harvest_date' => Carbon::now()->subDays(5),
                'available_from' => Carbon::now()->startOfDay(),
                'available_until' => Carbon::now()->endOfDay(),
                'fair_location' => 'Feira de Óbidos',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Cenoura',
                'slug' => 'cenoura',
                'description' => 'Cenouras frescas e crocantes, ricas em vitamina A',
                'price' => 1.80,
                'stock_quantity' => 80,
                'unit' => 'kg',
                'category_id' => 2, // Legumes
                'user_id' => 1, // João Silva
                'active' => true,
                'origin' => 'Quinta do João',
                'harvest_date' => Carbon::now()->subDays(3),
                'available_from' => Carbon::now()->startOfDay(),
                'available_until' => Carbon::now()->endOfDay(),
                'fair_location' => 'Feira de Santarém',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Queijo Fresco',
                'slug' => 'queijo-fresco',
                'description' => 'Queijo fresco artesanal, produzido com leite de cabra',
                'price' => 12.00,
                'stock_quantity' => 20,
                'unit' => 'unidade',
                'category_id' => 5, // Laticínios
                'user_id' => 3, // Pedro Oliveira
                'active' => true,
                'origin' => 'Quinta do Pedro',
                'harvest_date' => Carbon::now()->subDays(1),
                'available_from' => Carbon::now()->startOfDay(),
                'available_until' => Carbon::now()->endOfDay(),
                'fair_location' => 'Feira de Óbidos',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pão de Centeio',
                'slug' => 'pao-centeio',
                'description' => 'Pão de centeio artesanal, feito com fermento natural',
                'price' => 2.80,
                'stock_quantity' => 25,
                'unit' => 'unidade',
                'category_id' => 4, // Cereais
                'user_id' => 1, // João Silva
                'active' => true,
                'origin' => 'Padaria da Quinta',
                'harvest_date' => Carbon::now(),
                'available_from' => Carbon::now()->startOfDay(),
                'available_until' => Carbon::now()->endOfDay(),
                'fair_location' => 'Feira de Santarém',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Manjericão',
                'slug' => 'manjericao',
                'description' => 'Manjericão fresco aromático, perfeito para temperos',
                'price' => 1.50,
                'stock_quantity' => 40,
                'unit' => 'molho',
                'category_id' => 7, // Temperos
                'user_id' => 3, // Pedro Oliveira
                'active' => true,
                'origin' => 'Horta do Pedro',
                'harvest_date' => Carbon::now()->subDays(1),
                'available_from' => Carbon::now()->startOfDay(),
                'available_until' => Carbon::now()->endOfDay(),
                'fair_location' => 'Feira de Óbidos',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Mel de Eucalipto',
                'slug' => 'mel-eucalipto',
                'description' => 'Mel puro de eucalipto, colhido de apiários próprios',
                'price' => 15.00,
                'stock_quantity' => 15,
                'unit' => 'frasco',
                'category_id' => 8, // Conservas
                'user_id' => 1, // João Silva
                'active' => true,
                'origin' => 'Apiário da Quinta',
                'harvest_date' => Carbon::now()->subDays(30),
                'available_from' => Carbon::now()->startOfDay(),
                'available_until' => Carbon::now()->endOfDay(),
                'fair_location' => 'Feira de Santarém',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}
