<?php

// FAIL: database/migrations/2026_09_04_163640_create_complaints_table.php
//
// PERUBAHAN (permintaan penyelia #1):
// Lajur location_id (foreign key ke jadual locations) diganti dengan
// lajur teks 'location'. Pelajar kini menaip lokasi sendiri kerana
// venue di UPTM boleh berubah, dan admin tidak perlu menguruskan
// senarai lokasi lagi.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();

            // Siapa HANTAR aduan ini.
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Kategori yang classifier tetapkan. Kosong kalau tiada padanan.
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // BARU: lokasi ditaip sendiri oleh pelajar.
            // 150 aksara cukup untuk "Block B, Level 2, Lecture Room B201".
            $table->string('location', 150);

            // Siapa BAIKI aduan ini. Kosong sampai admin tugaskan.
            $table->foreignId('assigned_to')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->string('title');
            $table->text('description');
            $table->string('photo_path')->nullable();

            $table->enum('status', ['new', 'assigned', 'in_progress', 'completed'])
                  ->default('new');

            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
