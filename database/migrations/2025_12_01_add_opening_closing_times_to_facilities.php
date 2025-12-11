<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            // Add image column if it doesn't exist
            if (!Schema::hasColumn('facilities', 'image')) {
                $table->string('image')->nullable()->after('capacity');
            }
            
            // Add description column if it doesn't exist
            if (!Schema::hasColumn('facilities', 'description')) {
                $table->text('description')->nullable()->after('image');
            }
            
            // Add opening and closing time columns if they don't exist
            if (!Schema::hasColumn('facilities', 'opening_time')) {
                $table->time('opening_time')->nullable()->after('description');
            }
            
            if (!Schema::hasColumn('facilities', 'closing_time')) {
                $table->time('closing_time')->nullable()->after('opening_time');
            }
            
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('facilities', 'status')) {
                $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active')->after('closing_time');
            }
        });
    }

    public function down()
    {
        Schema::table('facilities', function (Blueprint $table) {
            // Dropping multiple columns in one go is cleaner and standard
            $table->dropColumn([
                'status',
                'closing_time',
                'opening_time',
                'description',
                'image'
            ]);
        });
    }
};