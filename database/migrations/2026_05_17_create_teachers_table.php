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
        Schema::create('teachers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nip')->nullable()->unique(); // Nomor Induk Pegawai
            $table->string('specialization')->nullable(); // Keahlian/Spesialisasi
            $table->string('certification_number')->nullable(); // Nomor Sertifikat
            $table->date('certification_date')->nullable(); // Tanggal Sertifikat
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
