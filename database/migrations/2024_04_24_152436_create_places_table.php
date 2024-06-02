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
        $states  = array_column(\App\Enums\PlaceStatus::cases(), 'value');
        Schema::create('places', function (Blueprint $table) use ($states) {
            $table->id();
            $table->string('name'); //unique
            $table->mediumText('description')->nullable();
            $table->enum('status', $states)->default('active');
            $table->foreignIdFor(\App\Models\User::class, 'created_by')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'updated_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
