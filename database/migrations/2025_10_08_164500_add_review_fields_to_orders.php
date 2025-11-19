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
            $table->boolean('can_review')->default(false)->after('delivery_status');
            $table->boolean('has_reviewed')->default(false)->after('can_review');
            $table->timestamp('picked_up_at')->nullable()->after('has_reviewed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['can_review', 'has_reviewed', 'picked_up_at']);
        });
    }
};
