<?php

// FAIL 5: STATUS_LOGS
// Arahan: php artisan make:migration create_status_logs_table
//
// Jadual ini menyimpan SEJARAH setiap perubahan status.
// Inilah yang menghasilkan garis masa dalam mock-up tadi.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('complaint_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Siapa yang buat perubahan ini (admin atau teknisyen).
            // Nama lajur bukan 'user_id', jadi kena tulis constrained('users').
            $table->foreignId('updated_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // nullable sebab rekod PERTAMA tiada status lama —
            // aduan baru dihantar, belum ada apa-apa sebelumnya.
            $table->string('old_status')->nullable();
            $table->string('new_status');

            $table->text('note')->nullable();   // catatan kerja teknisyen

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_logs');
    }
};