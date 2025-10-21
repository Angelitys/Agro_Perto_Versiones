<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MVPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuários de teste
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@agroperto.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'type' => 'admin',
            'phone' => '(11) 99999-9999',
            'address' => json_encode([
                'street' => 'Rua Principal',
                'number' => '123',
                'neighborhood' => 'Centro',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01000-000'
            ])
        ]);

        $producer1 = User::create([
            'name' => 'João Silva - Sítio Boa Vista',
            'email' => 'joao@sitioboavista.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'type' => 'producer',
            'phone' => '(11) 98888-8888',
            'address' => json_encode([
                'street' => 'Estrada Rural',
                'number' => '456',
                'neighborhood' => 'Zona Rural',
                'city' => 'Ibiúna',
                'state' => 'SP',
                'zip_code' => '18150-000'
            ])
        ]);

        $producer2 = User::create([
            'name' => 'Maria Santos - Fazenda Orgânica',
            'email' => 'maria@fazendaorganica.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'type' => 'producer',
            'phone' => '(11) 97777-7777',
            'address' => json_encode([
                'street' => 'Sítio das Flores',
                'number' => '789',
                'neighborhood' => 'Zona Rural',
                'city' => 'Cotia',
                'state' => 'SP',
                'zip_code' => '06700-000'
            ])
        ]);

        $customer = User::create([
            'name' => 'Ana Costa',
            'email' => 'ana@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'type' => 'customer',
            'phone' => '(11) 96666-6666',
            'address' => json_encode([
                'street' => 'Rua das Palmeiras',
                'number' => '321',
                'neighborhood' => 'Vila Madalena',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '05435-000'
            ])
        ]);

        // Criar categorias
        $categories = [
            [
                'name' => 'Verduras Frescas',
                'slug' => 'verduras-frescas',
                'description' => 'Verduras frescas colhidas diariamente',
                'icon' => '🥬'
            ],
            [
                'name' => 'Frutas Orgânicas',
                'slug' => 'frutas-organicas',
                'description' => 'Frutas orgânicas sem agrotóxicos',
                'icon' => '🍎'
            ],
            [
                'name' => 'Legumes',
                'slug' => 'legumes',
                'description' => 'Legumes frescos da horta',
                'icon' => '🥕'
            ],
            [
                'name' => 'Grãos e Cereais',
                'slug' => 'graos-cereais',
                'description' => 'Grãos e cereais naturais',
                'icon' => '🌾'
            ],
            [
                'name' => 'Laticínios',
                'slug' => 'laticinios',
                'description' => 'Produtos lácteos artesanais',
                'icon' => '🥛'
            ],
            [
                'name' => 'Mel Puro',
                'slug' => 'mel-puro',
                'description' => 'Mel puro direto do apiário',
                'icon' => '🍯'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Criar produtos
        $products = [
            // Produtos do João Silva
            [
                'name' => 'Alface Crespa Orgânica',
                'slug' => 'alface-crespa-organica',
                'description' => 'Alface crespa cultivada sem agrotóxicos, colhida na manhã do mesmo dia da entrega. Folhas verdes e crocantes, perfeitas para saladas.',
                'price' => 4.50,
                'stock_quantity' => 50,
                'unit' => 'maço',
                'category_id' => 1,
                'user_id' => $producer1->id,
                'origin' => 'Ibiúna - SP',
                'harvest_date' => now()->subDays(1),
                'available_from' => now(),
                'available_until' => now()->addDays(30),
                'max_daily_quantity' => 20,
                'pickup_locations' => json_encode(['Feira da Vila Madalena', 'Entrega em domicílio']),
                'pickup_instructions' => 'Disponível para retirada na feira aos sábados das 7h às 14h'
            ],
            [
                'name' => 'Tomate Cereja Orgânico',
                'slug' => 'tomate-cereja-organico',
                'description' => 'Tomates cereja doces e suculentos, cultivados em estufa sem uso de pesticidas. Ideais para saladas e aperitivos.',
                'price' => 8.90,
                'stock_quantity' => 30,
                'unit' => 'bandeja 250g',
                'category_id' => 1,
                'user_id' => $producer1->id,
                'origin' => 'Ibiúna - SP',
                'harvest_date' => now(),
                'available_from' => now(),
                'available_until' => now()->addDays(15),
                'max_daily_quantity' => 15,
                'pickup_locations' => json_encode(['Feira da Vila Madalena']),
                'pickup_instructions' => 'Retirada na feira aos sábados'
            ],
            [
                'name' => 'Cenoura Baby',
                'slug' => 'cenoura-baby',
                'description' => 'Cenouras baby doces e tenras, perfeitas para consumo in natura ou cozimento rápido. Cultivadas em solo rico em nutrientes.',
                'price' => 6.50,
                'stock_quantity' => 40,
                'unit' => 'maço 500g',
                'category_id' => 3,
                'user_id' => $producer1->id,
                'origin' => 'Ibiúna - SP',
                'harvest_date' => now()->subDays(2),
                'available_from' => now(),
                'available_until' => now()->addDays(20),
                'max_daily_quantity' => 20,
                'pickup_locations' => json_encode(['Feira da Vila Madalena', 'Entrega em domicílio']),
                'pickup_instructions' => 'Disponível para retirada na feira ou entrega'
            ],

            // Produtos da Maria Santos
            [
                'name' => 'Morango Orgânico',
                'slug' => 'morango-organico',
                'description' => 'Morangos orgânicos doces e aromáticos, cultivados sem agrotóxicos. Colhidos no ponto ideal de maturação.',
                'price' => 12.90,
                'stock_quantity' => 25,
                'unit' => 'bandeja 300g',
                'category_id' => 2,
                'user_id' => $producer2->id,
                'origin' => 'Cotia - SP',
                'harvest_date' => now(),
                'available_from' => now(),
                'available_until' => now()->addDays(7),
                'max_daily_quantity' => 10,
                'pickup_locations' => json_encode(['Feira Orgânica do Parque da Água Branca']),
                'pickup_instructions' => 'Retirada na feira aos domingos das 7h às 14h'
            ],
            [
                'name' => 'Mel de Flores Silvestres',
                'slug' => 'mel-flores-silvestres',
                'description' => 'Mel puro extraído de flores silvestres da região. Sabor suave e cristalização natural. Produto artesanal de alta qualidade.',
                'price' => 25.00,
                'stock_quantity' => 15,
                'unit' => 'pote 500g',
                'category_id' => 6,
                'user_id' => $producer2->id,
                'origin' => 'Cotia - SP',
                'harvest_date' => now()->subDays(30),
                'available_from' => now(),
                'available_until' => now()->addDays(365),
                'max_daily_quantity' => 5,
                'pickup_locations' => json_encode(['Feira Orgânica do Parque da Água Branca', 'Entrega em domicílio']),
                'pickup_instructions' => 'Disponível para retirada na feira ou entrega programada'
            ],
            [
                'name' => 'Queijo Minas Artesanal',
                'slug' => 'queijo-minas-artesanal',
                'description' => 'Queijo minas frescal artesanal produzido com leite de vacas criadas a pasto. Sabor suave e textura cremosa.',
                'price' => 18.50,
                'stock_quantity' => 20,
                'unit' => 'peça 400g',
                'category_id' => 5,
                'user_id' => $producer2->id,
                'origin' => 'Cotia - SP',
                'harvest_date' => now()->subDays(1),
                'available_from' => now(),
                'available_until' => now()->addDays(10),
                'max_daily_quantity' => 8,
                'pickup_locations' => json_encode(['Feira Orgânica do Parque da Água Branca']),
                'pickup_instructions' => 'Manter refrigerado. Retirada na feira aos domingos'
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $this->command->info('MVP Seeder executado com sucesso!');
        $this->command->info('Usuários criados:');
        $this->command->info('- Admin: admin@agroperto.com / password');
        $this->command->info('- Produtor 1: joao@sitioboavista.com / password');
        $this->command->info('- Produtor 2: maria@fazendaorganica.com / password');
        $this->command->info('- Cliente: ana@email.com / password');
    }
}

