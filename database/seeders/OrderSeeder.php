<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inserir pedidos de exemplo
        $orders = [
            [
                'order_number' => 'ORD-2025-0001', // CORREÇÃO: Adicionado campo obrigatório
                'user_id' => 2, // Maria Santos (consumidor)
                'total_amount' => 25.50,
                'status' => 'confirmed',
                'payment_method' => 'cash',
                'payment_status' => 'paid',
                'shipping_address' => json_encode([ // CORREÇÃO: Adicionado campo obrigatório
                    'street' => 'Rua Fictícia, 123',
                    'city' => 'Santarém',
                    'zip' => '2000-000',
                ]),
                'pickup_date' => Carbon::now()->addHours(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'order_number' => 'ORD-2025-0002', // CORREÇÃO: Adicionado campo obrigatório
                'user_id' => 4, // Ana Costa (consumidor) - ATENÇÃO: Verifique se o ID 4 existe no seu UserSeeder!
                'total_amount' => 18.20,
                'status' => 'preparing',
                'payment_method' => 'card',
                'payment_status' => 'paid',
                'shipping_address' => json_encode([ // CORREÇÃO: Adicionado campo obrigatório
                    'street' => 'Avenida Principal, 456',
                    'city' => 'Óbidos',
                    'zip' => '2510-000',
                ]),
                'pickup_date' => Carbon::now()->addHours(6),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($orders as $order) {
            $orderId = DB::table('orders')->insertGetId($order);

            // Itens do primeiro pedido
            if ($orderId == 1) {
                DB::table('order_items')->insert([
                    // CORREÇÃO: Ajuste dos nomes das colunas para bater com a migração
                    ['order_id' => $orderId, 'product_id' => 1, 'quantity' => 2, 'product_price' => 8.50, 'subtotal' => 17.00, 'product_name' => 'Tomate Cereja'],
                    ['order_id' => $orderId, 'product_id' => 2, 'quantity' => 3, 'product_price' => 2.50, 'subtotal' => 7.50, 'product_name' => 'Alface Americana'],
                    ['order_id' => $orderId, 'product_id' => 7, 'quantity' => 1, 'product_price' => 1.50, 'subtotal' => 1.50, 'product_name' => 'Manjericão'],
                ]);
            }

            // Itens do segundo pedido
            if ($orderId == 2) {
                DB::table('order_items')->insert([
                    // CORREÇÃO: Ajuste dos nomes das colunas para bater com a migração
                    ['order_id' => $orderId, 'product_id' => 3, 'quantity' => 5, 'product_price' => 3.20, 'subtotal' => 16.00, 'product_name' => 'Maçã Gala'],
                    ['order_id' => $orderId, 'product_id' => 4, 'quantity' => 1, 'product_price' => 1.80, 'subtotal' => 1.80, 'product_name' => 'Cenoura'],
                ]);
            }
        }

        // Inserir avaliações de exemplo
        $reviews = [
            [
                'user_id' => 2,
                'product_id' => 1,
                'order_id' => 1,
                'product_rating' => 5,
                'product_comment' => 'Tomates muito frescos e saborosos! Recomendo.',
                'is_verified' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'user_id' => 4,
                'product_id' => 3,
                'order_id' => 2,
                'product_rating' => 4,
                'product_comment' => 'Maçãs doces e crocantes. Muito bom!',
                'is_verified' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('reviews')->insert($reviews);
    }
}
