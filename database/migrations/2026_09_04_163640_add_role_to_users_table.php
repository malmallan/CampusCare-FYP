<?php
 
// FAIL 6: TAMBAH LAJUR role KE JADUAL users
// Arahan: php artisan make:migration add_role_to_users_table
//
// Jadual users DAH WUJUD (Laravel sediakan sendiri).
// Jadi kita guna Schema::table, BUKAN Schema::create.
//
//   Schema::create -> cipta jadual baru
//   Schema::table  -> ubah jadual sedia ada
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Satu jadual users untuk TIGA peranan.
            // Dibezakan oleh lajur ini sahaja.
            //
            // after('email') = letak lajur ini selepas lajur email
            // supaya kemas bila dilihat dalam phpMyAdmin.
            $table->enum('role', ['student', 'admin', 'technician'])
                  ->default('student')
                  ->after('email');
        });
    }
 
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
 