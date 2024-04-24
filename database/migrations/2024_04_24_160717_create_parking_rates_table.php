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
        $durations  = array_column(\App\Enums\ParkingRateDuration::cases(), 'value');
        Schema::create('parking_rates', function (Blueprint $table) use ($durations) {
            $table->id();
            $table->enum('duration', $durations)->default('thirty_minute');
            $table->decimal('rate', 8, 2);
            $table->foreignId('tariff_id')->constrained('tariffs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_rates');
    }
};
