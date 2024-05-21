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
        Schema::table('memberships', function (Blueprint $table) {
            $table->enum('discount_type', ['percentage', 'free', 'flat'])->default('percentage')->after('vehicle_id');
            $table->decimal('discount_amount')->nullable()->after('discount_type');
            $table->string('status')->default('pending')->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_amount', 'status']);
        });
    }
};
