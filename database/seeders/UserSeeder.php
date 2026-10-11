<?php

// FAIL: database/seeders/UserSeeder.php
//
// CARA MENAMBAH AKAUN:
//   Salin satu baris dalam senarai, tampal di bawahnya, tukar
//   nama dan emel. Setiap baris mesti berbentuk:
//
//       ['Nama Penuh', 'emel@domain'],
//
//   Jangan lupa koma di hujung, dan jangan guna emel yang sama dua kali.
//   Kemudian jalankan:  php artisan migrate:fresh --seed
//
// Semua akaun menggunakan kata laluan yang sama: password

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- ADMIN ----------
        $admins = [
            ['Puan Salmah binti Ahmad', 'salmah.ahmad@uptm.edu.my'],
           
        ];

        // ---------- TEKNISYEN (staf UPTM) ----------
        $technicians = [
            ['Encik Rahim bin Osman',     'rahim.osman@uptm.edu.my'],
            ['Encik Hafiz bin Kamal',     'hafiz.kamal@uptm.edu.my'],
            ['Encik Zulkifli bin Hassan', 'zulkifli.hassan@uptm.edu.my'],
            ['Muhammad Rusydan bin Ahmad', 'rusydan@uptm.edu.my'],
        ];

        // ---------- PELAJAR ----------
        $students = [
            ['Akmal bin Razali',         
                 'akmal@student.uptm.edu.my'],
            ['Adha bin Ali',   
                'adha@student.uptm.edu.my'],
            ['Badrul bin Kamal',    
                'badrul@student.uptm.edu.my'],
            ['Thaqif bin Putera',                 'thaqif@student.uptm.edu.my'],
            ['Luqman bin Ahmad',            
                'luqman@student.uptm.edu.my'],
            ['Siti  binti Yusof',       
                'siti@student.uptm.edu.my'],
            ['Irfan bin Azlan',                  'irfan@student.uptm.edu.my'],
            ['Amirul Hakim bin Roslan',      
                'amirul@student.uptm.edu.my'],
        ];

        // Tiga gelung di bawah melakukan kerja yang sama untuk setiap
        // peranan. Awak tak perlu sentuh bahagian ini bila menambah akaun.
        foreach ($admins as [$name, $email]) {
            $this->make($name, $email, 'admin');
        }

        foreach ($technicians as [$name, $email]) {
            $this->make($name, $email, 'technician');
        }

        foreach ($students as [$name, $email]) {
            $this->make($name, $email, 'student');
        }
    }

    private function make(string $name, string $email, string $role): void
    {
        User::create([
            'name'     => $name,
            'email'    => $email,
            // Model User akan hash kata laluan ini secara automatik.
            'password' => 'password',
            'role'     => $role,
        ]);
    }
}