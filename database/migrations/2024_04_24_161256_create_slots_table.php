<?php

use App\Enums\SlotStatus;
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
        $status = array_column(SlotStatus::cases(), 'value');
        Schema::create('slots', function (Blueprint $table) use ($status) {
            $table->id();
            $table->foreignId('place_id')->constrained('places')->onDelete('cascade');
            $table->foreignId('floor_id')->constrained('floors')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('cascade');
            $table->string('name', 191);
            $table->string('identity', 191)->nullable();
            $table->string('remarks', 191)->nullable();
            $table->enum('status', $status)->default(SlotStatus::available->value);
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
        Schema::dropIfExists('slots');
    }
};
