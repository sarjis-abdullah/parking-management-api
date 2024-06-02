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
            $table->foreignIdFor(\App\Models\Place::class, 'place_id')->nullable();
            $table->foreignIdFor(\App\Models\Slot::class, 'slot_id')->nullable();
            $table->foreignIdFor(\App\Models\Category::class, 'category_id')->nullable();
            $table->foreignIdFor(\App\Models\Floor::class, 'floor_id')->nullable();
            $table->foreignIdFor(\App\Models\Tariff::class, 'tariff_id')->nullable();
            $table->string('barcode', 191)->unique();
            $table->dateTime('in_time')->nullable();
            $table->dateTime('out_time')->nullable();
            $table->integer('duration')->nullable();
            $table->string('status')->nullable()->default('');
            $table->foreignIdFor(\App\Models\Vehicle::class, 'vehicle_id')->nullable();
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
        Schema::dropIfExists('parkings');
    }
};
