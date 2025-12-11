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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // classroom, conference_room, auditorium, laboratory, sports_facility, other
            $table->text('description')->nullable();
            $table->string('location');
            $table->integer('capacity');
            $table->integer('max_capacity')->nullable();
            $table->string('image')->nullable();
            $table->time('opening_time')->default('08:00:00');
            $table->time('closing_time')->default('17:00:00');
            $table->boolean('is_public')->default(true);
            $table->integer('buffer_time')->default(0); // in minutes
            $table->json('amenities')->nullable();
            $table->text('rules')->nullable();
            $table->boolean('status')->default(true); // active/inactive
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};