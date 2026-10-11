<?php

// FAIL: app/Console/Commands/TestClassifier.php
// Arahan: php artisan make:command TestClassifier
//
// Alat untuk mengukur ketepatan classifier awak.
// Jalankan dengan:  php artisan classifier:test
//
// Output arahan ini ialah data sebenar untuk Bab 4 laporan awak.

namespace App\Console\Commands;

use App\Models\Category;
use App\Services\ComplaintClassifier;
use Illuminate\Console\Command;

class TestClassifier extends Command
{
    protected $signature = 'classifier:test {--failures : Tunjukkan senarai kegagalan sahaja}';

    protected $description = 'Uji ketepatan ComplaintClassifier dengan 100 sampel aduan';

    /**
     * 100 sampel aduan dengan kategori yang SEPATUTNYA.
     * Campuran Bahasa Melayu dan Inggeris, ditulis seperti
     * cara pelajar sebenar menaip.
     */
    private function samples(): array
    {
        return [
            // ---------- IT / Wi-Fi (20) ----------
            ['Wifi slow in computer lab', 'IT / Wi-Fi'],
            ['Internet asyik disconnect', 'IT / Wi-Fi'],
            ['Wi-Fi tak boleh connect langsung', 'IT / Wi-Fi'],
            ['Computer in lab 2 cannot start', 'IT / Wi-Fi'],
            ['Komputer hang setiap kali buka', 'IT / Wi-Fi'],
            ['Printer library tak boleh print', 'IT / Wi-Fi'],
            ['Projector not showing anything', 'IT / Wi-Fi'],
            ['Network very slow during class', 'IT / Wi-Fi'],
            ['Rangkaian kampus down sejak pagi', 'IT / Wi-Fi'],
            ['Sambungan internet putus-putus', 'IT / Wi-Fi'],
            ['Laptop cannot connect to campus wifi', 'IT / Wi-Fi'],
            ['PC number 12 keeps restarting', 'IT / Wi-Fi'],
            ['Talian internet di perpustakaan lembab', 'IT / Wi-Fi'],
            ['The projector cable is missing', 'IT / Wi-Fi'],
            ['Wifi signal weak at cafeteria', 'IT / Wi-Fi'],
            ['Printer kehabisan dakwat dan tak respond', 'IT / Wi-Fi'],
            ['Computer lab network port not working', 'IT / Wi-Fi'],
            ['Internet connection keeps dropping', 'IT / Wi-Fi'],
            ['Komputer makmal tak boleh login', 'IT / Wi-Fi'],
            ['Projector image is blurry and flickering', 'IT / Wi-Fi'],

            // ---------- Electrical (20) ----------
            ['Lampu bilik kuliah tak menyala', 'Electrical'],
            ['Light in the hallway is broken', 'Electrical'],
            ['Kipas siling bunyi kuat', 'Electrical'],
            ['Fan not working in lecture room', 'Electrical'],
            ['Aircond tak sejuk langsung', 'Electrical'],
            ['Air conditioner leaking and not cold', 'Electrical'],
            ['Suis lampu rosak', 'Electrical'],
            ['Power socket not working', 'Electrical'],
            ['Soket plug terbakar sedikit', 'Electrical'],
            ['The switch does not turn on the light', 'Electrical'],
            ['Bilik gelap sebab lampu terbakar', 'Electrical'],
            ['Two lights above back row do not switch on', 'Electrical'],
            ['Penghawa dingin bocor air', 'Electrical'],
            ['Electric supply keeps tripping', 'Electrical'],
            ['Lampu koridor kelip-kelip', 'Electrical'],
            ['No power at the plug near the door', 'Electrical'],
            ['Kipas dinding tak berpusing', 'Electrical'],
            ['Lamp post outside is not lighting up', 'Electrical'],
            ['Aircond makes loud noise every morning', 'Electrical'],
            ['Suis di bilik mesyuarat tidak berfungsi', 'Electrical'],

            // ---------- Plumbing (20) ----------
            ['Tandas tersumbat di Blok B', 'Plumbing'],
            ['Toilet blocked and cannot flush', 'Plumbing'],
            ['Paip bocor dalam bilik air', 'Plumbing'],
            ['Water pipe leaking under the sink', 'Plumbing'],
            ['Sinki tersumbat di kafeteria', 'Plumbing'],
            ['No water supply in the female toilet', 'Plumbing'],
            ['Tap keeps running and cannot close', 'Plumbing'],
            ['Air tak keluar dari paip', 'Plumbing'],
            ['Drain outside is clogged', 'Plumbing'],
            ['Longkang tersumbat dan berbau', 'Plumbing'],
            ['Toilet bowl cracked and leaking', 'Plumbing'],
            ['Lantai bilik air banjir', 'Plumbing'],
            ['Water leaking from the ceiling', 'Plumbing'],
            ['Sink tap is dripping non stop', 'Plumbing'],
            ['Paip pecah di belakang bangunan', 'Plumbing'],
            ['Flush not working in male toilet', 'Plumbing'],
            ['Bilik air berbau busuk sangat', 'Plumbing'],
            ['Water pressure very low at level 2', 'Plumbing'],
            ['Sinki bocor bawah meja', 'Plumbing'],
            ['Toilet floor always wet and flooded', 'Plumbing'],

            // ---------- Facilities (20) ----------
            ['Kerusi patah di bilik kuliah', 'Facilities'],
            ['Chair leg is broken in room A101', 'Facilities'],
            ['Meja berlubang dan tak stabil', 'Facilities'],
            ['Table surface is damaged', 'Facilities'],
            ['Pintu bilik tak boleh tutup rapat', 'Facilities'],
            ['Door handle came off', 'Facilities'],
            ['Tingkap tak boleh ditutup', 'Facilities'],
            ['Window glass is loose', 'Facilities'],
            ['Papan putih tak boleh dipadam', 'Facilities'],
            ['Whiteboard marker holder missing', 'Facilities'],
            ['Lif tersangkut antara aras', 'Facilities'],
            ['Elevator button not responding', 'Facilities'],
            ['Bilik kuliah sangat kotor', 'Facilities'],
            ['Classroom is dirty after event', 'Facilities'],
            ['Dinding retak di koridor', 'Facilities'],
            ['Wall paint is peeling off', 'Facilities'],
            ['Desk in discussion room is wobbly', 'Facilities'],
            ['Kerusi perpustakaan banyak yang rosak', 'Facilities'],
            ['Door lock is stuck and hard to open', 'Facilities'],
            ['Meja kafeteria berhabuk dan kotor', 'Facilities'],

            // ---------- Safety (20) ----------
            ['Lantai licin selepas hujan', 'Safety'],
            ['Floor is slippery near the entrance', 'Safety'],
            ['Cermin pecah di tangga', 'Safety'],
            ['Broken glass on the walkway', 'Safety'],
            ['Wayar terdedah dekat dinding', 'Safety'],
            ['Exposed wire near the corridor', 'Safety'],
            ['Lampu kecemasan tak berfungsi', 'Safety'],
            ['Emergency light is not working', 'Safety'],
            ['Kunci rosak di pintu kecemasan', 'Safety'],
            ['Lock broken on the emergency exit', 'Safety'],
            ['Ada bahaya di kawasan pembinaan', 'Safety'],
            ['Dangerous hole on the walkway', 'Safety'],
            ['Bau kebakaran dari bilik elektrik', 'Safety'],
            ['Fire extinguisher is missing', 'Safety'],
            ['Kawasan parkir sangat bahaya waktu malam', 'Safety'],
            ['Slippery stairs without handrail', 'Safety'],
            ['Cermin pecah di bilik air perempuan', 'Safety'],
            ['Exposed wire hanging from ceiling', 'Safety'],
            ['Emergency light rosak di tangga kecemasan', 'Safety'],
            ['Broken glass door at the main entrance', 'Safety'],
        ];
    }

    public function handle(ComplaintClassifier $classifier): int
    {
        $samples = $this->samples();

        // Peta id kategori kepada namanya, supaya kita boleh
        // bandingkan nama dan bukan nombor.
        $names = Category::pluck('category_name', 'id')->toArray();

        if (empty($names)) {
            $this->error('Tiada kategori dalam database. Jalankan php artisan db:seed dahulu.');
            return self::FAILURE;
        }

        $correct  = 0;
        $failures = [];

        foreach ($samples as [$text, $expected]) {

            $id        = $classifier->classify($text, '');
            $predicted = $id ? ($names[$id] ?? 'UNKNOWN') : 'NONE';

            if ($predicted === $expected) {
                $correct++;
            } else {
                $failures[] = [$text, $expected, $predicted];
            }
        }

        $total    = count($samples);
        $accuracy = round($correct / $total * 100, 1);

        $this->newLine();
        $this->info("Sampel diuji : {$total}");
        $this->info("Betul        : {$correct}");
        $this->info("Salah        : " . ($total - $correct));
        $this->newLine();

        if ($accuracy >= 75) {
            $this->info("KETEPATAN    : {$accuracy}%  (sasaran 75% TERCAPAI)");
        } else {
            $this->warn("KETEPATAN    : {$accuracy}%  (sasaran 75% BELUM tercapai)");
        }
        $this->newLine();

        if ($failures) {
            $this->line('Senarai kegagalan:');
            $this->table(['Aduan', 'Sepatutnya', 'Diperoleh'], $failures);
            $this->newLine();
            $this->line('Setiap baris di atas menunjukkan keyword yang hilang');
            $this->line('atau bertindih. Tambah keyword dalam CategorySeeder,');
            $this->line('jalankan php artisan migrate:fresh --seed, dan uji semula.');
        }

        return self::SUCCESS;
    }
}
