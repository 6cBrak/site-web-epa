<?php

namespace Database\Seeders;

use App\Models\Antenne;
use Illuminate\Database\Seeder;

class AntenneSeeder extends Seeder
{
    public function run(): void
    {
        $antennes = [
            [
                'name' => 'EPA Ouaga 1 (Siège)',
                'slug' => 'ouaga',
                'address' => '1200 logements, Avenue Babangida, Ouagadougou',
                'phone' => '+226 70 14 32 48',
                'email' => 'centre-epa.bf@outlook.com',
            ],
            [
                'name' => 'EPA Bobo',
                'slug' => 'bobo',
                'address' => 'Sarfalao, secteur 17, Bobo-Dioulasso',
                'phone' => '+226 07 27 89 07',
                'email' => 'centre-epa.bf@outlook.com',
            ],
            [
                'name' => 'EPA Sahel (Dori)',
                'slug' => 'sahel-dori',
                'address' => 'Dori, secteur n°2',
                'phone' => '+226 76 83 63 71',
                'email' => 'centre-epa.bf@outlook.com',
            ],
        ];

        foreach ($antennes as $antenne) {
            Antenne::updateOrCreate(['slug' => $antenne['slug']], $antenne);
        }
    }
}
