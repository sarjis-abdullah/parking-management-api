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
        $states  = array_column(\App\Enums\TariffStatus::cases(), 'value');
        Schema::create('tariffs', function (Blueprint $table) use ($states) {
            $table->id();
            $table->foreignId('place_id')->constrained('places')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name');
            $table->decimal('min_amount', 8, 2)->nullable();
            $table->datetime('start_date')->default(now());
            $table->datetime('end_date')->nullable();
            $table->enum('status', $states)->default('enabled');
            $table->foreignId( 'created_by')->constrained('users')->onDelete('cascade');
            $table->foreignIdFor(\App\Models\User::class, 'updated_by')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tariffs');
    }
};
