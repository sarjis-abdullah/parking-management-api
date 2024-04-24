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
        $states  = array_column(\App\Enums\CategoryStatus::cases(), 'value');
        Schema::create('categories', function (Blueprint $table) use ($states) {
            $table->id();
            $table->foreignIdFor(\App\Models\Place::class); //unique
            $table->string('name'); //unique
            $table->mediumText('description')->nullable();
            $table->tinyInteger('limit_count')->default(1);
            $table->enum('status', $states)->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'created_by');
            $table->foreignIdFor(\App\Models\User::class, 'updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
