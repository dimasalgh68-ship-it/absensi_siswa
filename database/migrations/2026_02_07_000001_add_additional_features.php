<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the group enum to include 'teacher' and 'student'
        DB::statement("ALTER TABLE users MODIFY COLUMN `group` ENUM('user', 'admin', 'superadmin', 'teacher', 'student') NOT NULL");

        // Create settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'app_logo',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'app_name',
                'value' => 'Presensi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop settings table
        Schema::dropIfExists('settings');

        // Revert back to original enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN `group` ENUM('user', 'admin', 'superadmin') NOT NULL");
    }
};
