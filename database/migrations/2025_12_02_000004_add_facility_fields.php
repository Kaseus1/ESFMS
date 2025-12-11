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
            // Add image column if it doesn't exist
            if (!Schema::hasColumn('facilities', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            
            // Add description column if it doesn't exist
            if (!Schema::hasColumn('facilities', 'description')) {
                $table->text('description')->nullable()->after('location');
            }
            
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('facilities', 'status')) {
                $table->boolean('status')->default(true)->after('is_public');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['image', 'description', 'status']);
        });
    }
};