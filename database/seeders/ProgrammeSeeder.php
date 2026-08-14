<?php

namespace Database\Seeders;

use App\Models\Programme;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    public function run(): void
    {
        $programmes = [
            [
                'name_fr' => 'Informatique & Digitalisation',
                'name_en' => 'IT & Digitalization',
                'slug' => 'informatique-digitalisation',
                'color' => '#E4572E',
                'order' => 1,
            ],
            [
                'name_fr' => 'Action Humanitaire & Développement',
                'name_en' => 'Humanitarian Action & Development',
                'slug' => 'action-humanitaire-developpement',
                'color' => '#2E7D32',
                'order' => 2,
            ],
        ];

        foreach ($programmes as $programme) {
            Programme::updateOrCreate(['slug' => $programme['slug']], $programme);
        }
    }
}
