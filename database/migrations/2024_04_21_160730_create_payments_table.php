<?php

use App\Enums\PaymentStatus;
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
        $states  = array_column(PaymentStatus::cases(), 'value');
        Schema::create('payments', function (Blueprint $table) use ($states) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->enum('status', $states);
            $table->integer('date');
            $table->integer('amount');
            $table->integer('receivedAmount')->nullable();
            $table->integer('changedAmount')->nullable();
            $table->string('method');
            $table->string('referenceNumber')->nullable();
            $table->string('transactionNumber')->nullable();
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
