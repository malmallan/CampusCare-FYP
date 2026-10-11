<?php

// FAIL: database/migrations/2026_09_04_163639_create_locations_table.php
//
// PERUBAHAN: lajur 'block' dibuang kerana UPTM tiada blok,
// hanya tingkat 1 hingga 5.
//
// Jadual ini kini senarai CADANGAN sahaja. Ia tidak lagi dihubungkan
// ke jadual complaints dengan foreign key, kerana pelajar boleh
// menaip venue yang tiada dalam senarai.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('floor');       // Level 1 hingga Level 5, atau Outdoor
            $table->string('room_name');   // Computer Lab 1, Prayer Room
            $table->timestamps();

            // Halang bilik yang sama dimasukkan dua kali di tingkat yang sama.
            $table->unique(['floor', 'room_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
