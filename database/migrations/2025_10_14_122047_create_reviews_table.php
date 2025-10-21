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
        Schema::create("reviews", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade"); // User who left the review
            $table->foreignId("product_id")->constrained("products")->onDelete("cascade");
            $table->foreignId("producer_id")->nullable()->constrained("users")->onDelete("cascade"); // Producer being reviewed
            $table->foreignId("order_id")->nullable()->constrained("orders")->onDelete("cascade");
            $table->integer("product_rating")->unsigned()->min(1)->max(5)->nullable();
            $table->integer("producer_rating")->unsigned()->min(1)->max(5)->nullable();
            $table->text("product_comment")->nullable();
            $table->text("producer_comment")->nullable();
            $table->json("photos")->nullable();
            $table->boolean("is_verified")->default(false);
            $table->boolean("is_public")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
