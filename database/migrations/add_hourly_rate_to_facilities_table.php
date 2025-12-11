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
        // Only run if hourly_rate doesn't exist
        if (!Schema::hasColumn('facilities', 'hourly_rate')) {
            Schema::table('facilities', function (Blueprint $table) {
                $table->decimal('hourly_rate', 10, 2)->default(0)->after('description');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('facilities', 'hourly_rate')) {
            Schema::table('facilities', function (Blueprint $table) {
                $table->dropColumn('hourly_rate');
            });
        }
    }
};