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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('method',)->default(\App\Enums\PaymentMethod::cash->value);
            $table->decimal('payable_amount', 8, 2)->default(0.00);
            $table->decimal('paid_amount', 8, 2)->default(0.00);
            $table->decimal('due_amount', 8, 2)->default(0.00);
            $table->string('status')->default('pending');
            $table->foreignId( 'received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId( 'paid_by_vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId( 'parking_id')->nullable()->constrained('parkings')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
