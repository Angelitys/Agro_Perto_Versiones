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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity');
            $table->string('unit'); // kg, unidade, litro, etc.
            $table->json('images')->nullable(); // Array de URLs das imagens
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Produtor
            $table->boolean('active')->default(true);
            $table->string('origin')->nullable(); // Origem do produto
            $table->date('harvest_date')->nullable(); // Data da colheita
            $table->string('fair_location')->nullable(); // <-- ADICIONE ESTA LINHA
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
