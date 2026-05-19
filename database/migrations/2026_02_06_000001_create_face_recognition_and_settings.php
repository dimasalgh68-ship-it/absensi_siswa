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

        // Create face_registrations table
        Schema::create('face_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade');
            $table->text('face_embedding'); // JSON array of 128-d or 512-d vector
            $table->string('photo_path')->nullable(); // Backup foto asli di storage
            $table->boolean('is_active')->default(true);
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('is_active');
        });

        // Create office_locations table
        Schema::create('office_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('radius_meters')->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['latitude', 'longitude']);
            $table->index('is_active');
        });

        // Create academic_events table
        Schema::create('academic_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['holiday', 'exam', 'event', 'meeting', 'other'])->default('event');
            $table->string('color', 7)->default('#3B82F6');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create settings table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Add face validation fields to attendances table
        Schema::table('attendances', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['user_id']);
            
            // Add foreign key with cascade delete
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Face validation data for clock in
            $table->string('face_photo_path')->nullable()->after('longitude');
            $table->decimal('face_similarity_score', 5, 2)->nullable()->after('face_photo_path');
            
            // Face validation data for clock out
            $table->string('face_photo_out_path')->nullable()->after('face_similarity_score');
            $table->decimal('face_similarity_score_out', 5, 2)->nullable()->after('face_photo_out_path');
            
            // Validation method: 'face', 'manual', 'system'
            $table->enum('validation_method', ['face', 'manual', 'system'])->default('face')->after('face_similarity_score_out');
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'app_logo', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_name', 'value' => 'Presensi', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'gps_anti_spoofing_enabled', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'gps_anti_spoofing_threshold', 'value' => '50', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop settings table
        Schema::dropIfExists('settings');

        // Drop academic_events table
        Schema::dropIfExists('academic_events');

        // Drop face validation fields from attendances
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'face_photo_path',
                'face_similarity_score',
                'face_photo_out_path',
                'face_similarity_score_out',
                'validation_method'
            ]);
            
            // Restore original foreign key without cascade
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users');
        });

        // Drop tables
        Schema::dropIfExists('office_locations');
        Schema::dropIfExists('face_registrations');

        // Revert back to original enum values
        DB::statement("ALTER TABLE users MODIFY COLUMN `group` ENUM('user', 'admin', 'superadmin') NOT NULL");
    }
};
