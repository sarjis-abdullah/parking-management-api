<?php

use App\Enums\PaymentMethod;
use App\Enums\TariffStatus;
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
        Schema::create('parkings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('place_id')->constrained('places')->onDelete('cascade');
            $table->foreignId('slot_id')->constrained('slots')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('floor_id')->constrained('floors')->onDelete('cascade');
            $table->foreignId('tariff_id')->constrained('tariffs')->onDelete('cascade');
            $table->string('barcode', 191)->unique();
            $table->dateTime('in_time')->nullable();
            $table->dateTime('out_time')->nullable();
            $table->integer('duration')->nullable();
            $table->string('status')->nullable()->default('');
            $table->foreignId( 'vehicle_id')->nullable()->constrained('vehicles');
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
        Schema::dropIfExists('parkings');
    }
};
