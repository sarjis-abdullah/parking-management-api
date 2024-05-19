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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('number', 191);
            $table->string('driver_name', 191)->nullable();
            $table->string('driver_mobile', 191)->nullable();
            $table->string('status')->nullable()->default('checked-in');
            $table->foreignId('membership_id')->nullable()->constrained('memberships');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
