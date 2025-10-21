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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('delivery_notes')->nullable(); // Observações da entrega
            $table->string('delivery_photo')->nullable(); // Foto da entrega
            $table->boolean('customer_confirmed')->default(false); // Cliente confirmou recebimento
            $table->timestamp('customer_confirmed_at')->nullable(); // Data da confirmação do cliente
            $table->text('customer_feedback')->nullable(); // Feedback do cliente sobre a entrega
            $table->integer('delivery_rating')->nullable(); // Avaliação da entrega (1-5)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_notes',
                'delivery_photo',
                'customer_confirmed',
                'customer_confirmed_at',
                'customer_feedback',
                'delivery_rating'
            ]);
        });
    }
};

