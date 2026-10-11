<?php



namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'name'       => 'IT / Wi-Fi',
                'department' => 'IT Unit',
                'keywords'   => [
                   
                    'wifi', 'wi-fi', 'internet', 'network', 'connection',
                    'laptop', 'computer', 'pc', 'printer', 'projector',
                    
                    'rangkaian', 'sambungan', 'komputer', 'talian',
                ],
            ],
            [
                'name'       => 'Electrical',
                'department' => 'Electrical Unit',
                'keywords'   => [
                    'light', 'lamp', 'fan', 'switch', 'plug', 'socket',
                    'power', 'electric', 'dark', 'aircond', 'air conditioner',
                    'lampu', 'kipas', 'suis', 'soket', 'elektrik', 'gelap', 'penghawa',
                ],
            ],
            [
                'name'       => 'Plumbing',
                'department' => 'Plumbing Unit',
                'keywords'   => [
                    'pipe', 'water', 'leak', 'leaking', 'sink', 'tap',
                    'toilet', 'blocked', 'clogged', 'drain', 'flood',
                    'paip', 'air', 'bocor', 'sinki', 'tandas', 'tersumbat',
                    'longkang', 'banjir', 'paip pecah',
                ],
            ],
            [
                'name'       => 'Facilities',
                'department' => 'Facilities Unit',
                'keywords'   => [
                    'chair', 'table', 'desk', 'door', 'window', 'whiteboard',
                    'dirty', 'broken furniture', 'lift', 'elevator', 'wall',
                    'kerusi', 'meja', 'pintu', 'tingkap', 'papan putih', 'kotor',
                ],
            ],
            [
                'name'       => 'Safety',
                'department' => 'Safety Unit',
                'keywords'   => [
                    'slippery', 'danger', 'dangerous', 'broken glass', 'fire',
                    'emergency light', 'lock broken', 'exposed wire',
                    'licin', 'bahaya', 'cermin pecah', 'kebakaran',
                    'kunci rosak', 'wayar terdedah',
                ],
            ],
        ];

        foreach ($data as $item) {

            
            $categoryId = DB::table('categories')->insertGetId([
                'category_name' => $item['name'],
                'department'    => $item['department'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            foreach ($item['keywords'] as $word) {
                DB::table('keywords')->insert([
                    'category_id' => $categoryId,
                    'keyword'     => $word,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}