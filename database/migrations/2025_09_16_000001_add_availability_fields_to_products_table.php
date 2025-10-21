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
        Schema::table('products', function (Blueprint $table) {
            $table->json('availability_schedule')->nullable(); // Horários de disponibilidade
            $table->date('available_from')->nullable(); // Data de início da disponibilidade
            $table->date('available_until')->nullable(); // Data final da disponibilidade
            $table->integer('max_daily_quantity')->nullable(); // Quantidade máxima por dia
            $table->json('pickup_locations')->nullable(); // Locais de retirada
            $table->text('pickup_instructions')->nullable(); // Instruções para retirada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'availability_schedule',
                'available_from',
                'available_until',
                'max_daily_quantity',
                'pickup_locations',
                'pickup_instructions'
            ]);
        });
    }
};

