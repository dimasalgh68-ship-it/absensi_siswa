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
            $table->integer('radius')->default(100); // in meters
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['latitude', 'longitude']);
            $table->index('is_active');
        });

        // Add face validation fields to attendances table
        Schema::table('attendances', function (Blueprint $table) {
            // Face validation data for clock in
            $table->string('face_photo_path')->nullable()->after('longitude');
            $table->decimal('face_similarity_score', 5, 2)->nullable()->after('face_photo_path');
            
            // Face validation data for clock out
            $table->string('face_photo_out_path')->nullable()->after('face_photo_path');
            $table->decimal('face_similarity_score_out', 5, 2)->nullable()->after('face_similarity_score');
            
            // Validation method: 'face', 'manual'
            $table->enum('validation_method', ['face', 'manual'])->default('face')->after('face_similarity_score_out');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop face validation fields from attendances
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'face_photo_path',
                'face_similarity_score',
                'face_photo_out_path',
                'face_similarity_score_out',
                'validation_method'
            ]);
        });

        // Drop tables
        Schema::dropIfExists('office_locations');
        Schema::dropIfExists('face_registrations');
    }
};
