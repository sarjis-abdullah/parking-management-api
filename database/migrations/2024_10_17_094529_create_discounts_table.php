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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['percentage', 'flat', 'promo_code', 'time_based', 'loyalty', 'bulk'])->default('percentage');
            $table->decimal('amount', 8, 2); // Value of discount (percentage or flat amount)
            $table->string('promo_code')->nullable(); // Promo code if applicable
            $table->integer('valid_for_hours')->nullable(); // For time-based discounts, duration in minutes
            $table->integer('valid_after_visits')->nullable(); // For loyalty discounts, number of visits
            $table->boolean('is_active')->default(true); // Whether discount is active
            $table->boolean('allow_separate_discount')->default(false); // Whether discount is active
            $table->dateTime('start_date')->nullable(); // Whether discount is active
            $table->dateTime('end_date')->nullable(); // Whether discount is active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
