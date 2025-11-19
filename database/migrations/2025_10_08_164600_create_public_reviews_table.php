<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('public_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cliente que avaliou
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Produto avaliado
            $table->foreignId('producer_id')->constrained('users')->onDelete('cascade'); // Produtor avaliado
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Pedido relacionado
            $table->integer('product_rating')->unsigned(); // Nota do produto (1-5)
            $table->integer('producer_rating')->unsigned(); // Nota do produtor (1-5)
            $table->text('product_comment')->nullable(); // Comentário sobre o produto
            $table->text('producer_comment')->nullable(); // Comentário sobre o produtor
            $table->json('photos')->nullable(); // Fotos da avaliação
            $table->boolean('is_verified')->default(true); // Avaliação verificada (compra confirmada)
            $table->boolean('is_public')->default(true); // Avaliação pública
            $table->timestamp('reviewed_at');
            $table->timestamps();

            // Índices para performance
            $table->index(['product_id', 'is_public']);
            $table->index(['producer_id', 'is_public']);
            $table->index(['user_id']);
            
            // Garantir que um usuário só pode avaliar um produto por pedido uma vez
            $table->unique(['user_id', 'product_id', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_reviews');
    }
};
