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
        Schema::create('students', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nis')->nullable()->unique(); // Nomor Induk Siswa
            $table->string('class')->nullable(); // Kelas
            $table->enum('status', ['active', 'inactive', 'graduated', 'dropped_out'])->default('active');
            $table->date('enrollment_date')->nullable(); // Tanggal Pendaftaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
