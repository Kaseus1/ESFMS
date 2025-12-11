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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->string('event_name');
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('participants')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            
            // Recurring event fields
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_type')->nullable(); // daily, weekly, monthly
            $table->date('recurrence_end_date')->nullable();
            $table->json('recurrence_days')->nullable(); // for weekly recurrence
            
            // Additional event fields
            $table->string('event_type')->nullable(); // meeting, class, seminar, etc.
            $table->boolean('requires_setup')->default(false);
            $table->text('setup_requirements')->nullable();
            $table->boolean('requires_equipment')->default(false);
            $table->text('equipment_needed')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};