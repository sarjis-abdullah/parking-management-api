<?php

use App\Enums\OrderType;
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
        $order_types  = array_column(OrderType::cases(), 'value');
        Schema::create('orders', function (Blueprint $table) use ($order_types) {
            $table->id();
            $table->enum('status', ['ready for kitchen', 'delivered']);
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('type', $order_types);
            $table->date('pickup_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
