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
        // Create tasks table
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('assigned_to', ['all_users', 'specific_users']);
            $table->datetime('due_date');
            $table->foreignUlid('created_by')->constrained('users');
            $table->string('image_path')->nullable();
            $table->string('link')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Create task_assignments table
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
        });

        // Create task_submissions table
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id');
            $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade');
            $table->text('submission')->nullable(); // Made nullable
            $table->string('status')->default('pending');
            $table->string('image_path')->nullable();
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->unique(['task_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
        Schema::dropIfExists('task_assignments');
        Schema::dropIfExists('tasks');
    }
};
