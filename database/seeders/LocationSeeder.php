<?php



namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
             'Level G' => [
                'Computer Lab 1',
                'Computer Lab 2',
                'Cafeteria',
                'Male Toilet',
                'Female Toilet',

            'Level 1' => [
                'Lecture Room 1.1',
                'Lecture Room 1.2',
                'Lecture Room 1.3',
                'Lecture Room 1.4',
                'Lecture Room 1.5',
                'Library',
                'Discussion Room 1',
                'Student Affairs Office',
                'Prayer Room',
                'Male Toilet',
                'Female Toilet',
            ],

            'Level 2' => [
                'Computer Lab 3',
                'Computer Lab 4',
                'Network Lab 1',
                'Network Lab 2',
                'Lecture Room 2.1',
                'Lecture Room 2.2',
                'Lecture Room 2.3',
                'Lecture Room 2.4',
                'Lecture Room 2.5',
                'Meeting Room',
                'Male Toilet',
                'Female Toilet',

            ],

            'Level 3' => [
                'Lecturer Office 1',
                'Lecturer Office 2',
                'Main Lecture Hall',
                'Male Toilet',
                'Female Toilet',

            ],

            'Level 4' => [
                'Lecture Room 4.1',
                'Lecture Room 4.2',
                'Lecture Room 4.3',
                'Lecture Room 4.4',
                'Lecture Room 4.5',
                'Male Toilet',
                'Female Toilet',

            ],

            'Level 5' => [
                'Lecture Room 5.1',
                'Lecture Room 5.2',
                'Lecture Room 5.3',
                'Lecture Room 5.4',
                'Lecture Room 5.5',
                'Male Toilet',
                'Female Toilet',

            ],

            'Outdoor' => [
                'Main Car Park',
                'Futsal Court',
            ],
        ];

        foreach ($locations as $floor => $rooms) {
            foreach ($rooms as $room) {
                DB::table('locations')->insert([
                    'floor'      => $floor,
                    'room_name'  => $room,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
