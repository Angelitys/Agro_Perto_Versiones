<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar o enum para incluir o novo status 'awaiting_confirmation'
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'awaiting_confirmation', 'confirmed', 'preparing', 'shipped', 'delivered', 'cancelled', 'rejected') DEFAULT 'pending'");
        
        // Adicionar campos para confirmação do produtor
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('producer_confirmed_at')->nullable()->after('customer_confirmed_at');
            $table->text('producer_rejection_reason')->nullable()->after('producer_confirmed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover os campos adicionados
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['producer_confirmed_at', 'producer_rejection_reason']);
        });
        
        // Reverter o enum ao estado anterior
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'preparing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
    }
};
