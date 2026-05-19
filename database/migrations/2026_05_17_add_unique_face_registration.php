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
        Schema::table('face_registrations', function (Blueprint $table) {
            // HIGH SEVERITY BUG FIX #16: Add unique constraint
            // Only one active registration per user
            // This prevents multiple active face registrations for the same user
            $table->unique(['user_id', 'is_active'], 'unique_active_face_per_user')
                ->where('is_active', true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('face_registrations', function (Blueprint $table) {
            $table->dropUnique('unique_active_face_per_user');
        });
    }
};
