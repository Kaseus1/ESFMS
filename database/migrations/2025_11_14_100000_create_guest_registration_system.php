<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add guest-specific fields to users table if not exists
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'contact_number')) {
                $table->string('contact_number')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'purpose')) {
                $table->text('purpose')->nullable()->after('contact_number');
            }
            if (!Schema::hasColumn('users', 'organization')) {
                $table->string('organization')->nullable()->after('purpose');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('organization');
            }
            if (!Schema::hasColumn('users', 'status_updated_at')) {
                $table->timestamp('status_updated_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('status_updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['approved_by']);
            $table->dropColumnIfExists(['contact_number', 'purpose', 'organization', 'status', 'status_updated_at', 'approved_by']);
        });
    }
};