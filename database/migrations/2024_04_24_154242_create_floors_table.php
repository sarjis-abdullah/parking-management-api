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
        $states  = array_column(\App\Enums\FloorStatus::cases(), 'value');
        Schema::create('floors', function (Blueprint $table) use ($states) {
            $table->id();
            $table->foreignId('place_id')->constrained('places')->onDelete('cascade');
            $table->string('name'); //name
            $table->tinyInteger('level')->default(0);
            $table->string('remarks', 191)->nullable();
            $table->enum('status', $states)->default('active');
            $table->foreignIdFor(\App\Models\User::class, 'created_by')->nullable();
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
        Schema::dropIfExists('floors');
    }
};
