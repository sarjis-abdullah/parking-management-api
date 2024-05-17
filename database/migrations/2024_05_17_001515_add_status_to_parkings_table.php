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
        if (!Schema::hasColumn('parkings', 'status')){
            Schema::table('parkings', function (Blueprint $table) {
                $table->string('status')->nullable()->default(0)->after('out_time');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('parkings', 'status'))
        {
            Schema::table('parkings', function (Blueprint $table)
            {
                $table->dropColumn('parkings');
            });
        }
    }
};
