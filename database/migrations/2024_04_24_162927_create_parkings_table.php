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
        $states  = array_column(TariffStatus::cases(), 'value');
        $payment_methods  = array_column(PaymentMethod::cases(), 'value');
        Schema::create('parkings', function (Blueprint $table) use ($states, $payment_methods) {
            $table->id();
            $table->foreignId('place_id')->constrained('places')->onDelete('cascade');
            $table->foreignId('slot_id')->constrained('slots')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('barcode', 191)->unique();
            $table->string('vehicle_no', 191);
            $table->string('driver_name', 191)->nullable();
            $table->string('driver_mobile', 191)->nullable();
            $table->dateTime('in_time');
            $table->dateTime('out_time')->nullable();
            $table->enum('payment_method', $payment_methods)->default(PaymentMethod::cash->value);
            $table->decimal('payable_amount', 8, 2)->default(0.00);
            $table->decimal('paid_amount', 8, 2)->default(0.00);
            $table->decimal('due_amount', 8, 2)->default(0.00);
            $table->enum('status', $states)->default(TariffStatus::enabled->value);
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
