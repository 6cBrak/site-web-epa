<?php

namespace Database\Seeders;

use App\Models\Antenne;
use App\Models\Formation;
use App\Models\Programme;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FormationSeeder extends Seeder
{
    public function run(): void
    {
        $informatique = Programme::where('slug', 'informatique-digitalisation')->firstOrFail();
        $humanitaire = Programme::where('slug', 'action-humanitaire-developpement')->firstOrFail();
        $antenneIds = Antenne::pluck('id');

        $informatiqueFormations = [
            'Informatique général (Office 365)',
            'Intelligence Artificielle (IA)',
            'Data Analytics',
            'Développement Web / Applications',
            'Infographie & Montage vidéo',
            'Marketing & Innovations digitales',
            'Finance digitale / Fintech',
            'Réseaux & Maintenance informatique',
            'Énergie renouvelable',
            'Électrotechnique',
            'Community Management',
            'Entrepreneuriat',
            'Management des entreprises',
            'Secrétariat Comptabilité',
        ];

        $humanitaireFormations = [
            'Gestion de projet humanitaire / développement',
            'Suivi-évaluation (MEAL)',
            'Gestion RH & Management des ONG/associations',
            'Finance-comptabilité des ONG/associations',
            'Logistique humanitaire (LH)',
            'WASH (Eau-Hygiène-Assainissement)',
            'Sécurité alimentaire (SAME)',
            'Agro-alimentaire',
            "Protection de l'enfance (PESU)",
            'Plaidoyer et communication humanitaire',
            'Prise en charge psychosociale',
            'Paix & gestion pacifique des conflits',
            'Genre & inclusion pour le développement',
            'Négociation et accès humanitaire',
        ];

        $this->seedFormations($informatique, $informatiqueFormations, $antenneIds);
        $this->seedFormations($humanitaire, $humanitaireFormations, $antenneIds);
    }

    /**
     * @param  array<int, string>  $titles
     * @param  \Illuminate\Support\Collection<int, int>  $antenneIds
     */
    private function seedFormations(Programme $programme, array $titles, $antenneIds): void
    {
        foreach ($titles as $index => $title) {
            $formation = Formation::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'programme_id' => $programme->id,
                    'title_fr' => $title,
                    'title_en' => $title,
                    'published' => true,
                    'order' => $index + 1,
                ]
            );

            $formation->antennes()->syncWithoutDetaching($antenneIds);
        }
    }
}
