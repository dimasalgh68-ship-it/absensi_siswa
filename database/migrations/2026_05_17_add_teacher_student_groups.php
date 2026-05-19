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
        // Add teacher and student groups to users table
        Schema::table('users', function (Blueprint $table) {
            // Change enum to include teacher and student
            $table->enum('group', ['user', 'admin', 'superadmin', 'teacher', 'student'])
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Delete users with teacher or student group first
            // Then revert to original enum
            $table->enum('group', ['user', 'admin', 'superadmin'])
                ->default('user')
                ->change();
        });
    }
};
