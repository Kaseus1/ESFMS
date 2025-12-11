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
        Schema::table('facilities', function (Blueprint $table) {
            // Add guest_accessible column
            // true = guests can book this facility
            // false = only students/faculty can book
            // null = defaults to true (accessible)
            $table->boolean('guest_accessible')->nullable()->default(true)->after('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('guest_accessible');
        });
    }
};