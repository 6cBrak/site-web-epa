<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('hero_slide_delay_seconds', '4.5');

        $texts = [
            'nav_home' => ['Accueil', 'Home'],
            'nav_about' => ['Qui sommes-nous', 'About us'],
            'nav_formations' => ['Nos formations', 'Our courses'],
            'nav_actualites' => ['Actualités', 'News'],
            'nav_contact' => ['Contact', 'Contact'],
            'nav_cta' => ["S'inscrire", 'Apply'],
            'header_tagline_line1' => ['Centre de formation', 'Training center'],
            'header_tagline_line2' => ['Informatique & Action Humanitaire', 'IT & Humanitarian Action'],

            'hero_kicker' => ['Former les acteurs du développement', 'Training the changemakers'],
            'hero_title' => [
                'Des formations professionnelles en Informatique & Action Humanitaire',
                'Professional training in IT & Humanitarian Action',
            ],
            'hero_subtitle' => [
                "EPA_BURKINA forme les jeunes professionnels d'Afrique, du niveau BEPC au BAC+, pour booster leur employabilité dans les ONG et les entreprises.",
                "EPA_BURKINA trains young African professionals, from BEPC to Bachelor's level, to boost their employability in NGOs and businesses.",
            ],
            'hero_cta_primary' => ['S\'inscrire à une formation', 'Apply for a course'],
            'hero_cta_secondary' => ['Voir les formations', 'See our courses'],

            'home_programmes_title' => ['Nos programmes de formation', 'Our training programmes'],
            'home_programmes_subtitle' => [
                "Deux pôles d'excellence, {count} formations disponibles",
                'Two areas of expertise, {count} courses available',
            ],
            'home_advantage_1' => ['Formations innovantes', 'Innovative training'],
            'home_advantage_2' => ['Certification prestigieuse', 'Recognized certification'],
            'home_advantage_3' => ['Accompagnement permanent', 'Ongoing support'],
            'home_advantage_4' => ['Réseau puissant de partenaires', 'Strong partner network'],
            'home_antennes_title' => ['Nos antennes', 'Our centers'],
            'home_actualites_title' => ['Actualités', 'News'],
            'home_actualites_link' => ['Voir toutes les actualités →', 'See all news →'],
            'home_partenaires_title' => ['Nos partenaires', 'Our partners'],
            'home_partenaires_subtitle' => ['Ils nous font confiance', 'They trust us'],
            'home_cta_title' => ["Prêt à booster votre employabilité ?", 'Ready to boost your employability?'],
            'home_cta_subtitle' => [
                "Rejoignez EPA dès aujourd'hui, du niveau BEPC au BAC+.",
                "Join EPA today, from BEPC to Bachelor's level.",
            ],
            'home_cta_button' => ['S\'inscrire maintenant', 'Apply now'],

            'about_title' => ['Qui sommes-nous ?', 'About us'],
            'about_intro' => [
                "EPA_BURKINA est un centre de formation professionnelle spécialisé dans les métiers de l'Informatique & de l'Action Humanitaire. Implanté au Burkina Faso depuis plusieurs années, EPA répond aux besoins croissants en compétences qualifiées des ONG, associations et entreprises qui contribuent au développement du pays. Grâce à son équipe de consultants formateurs nationaux et internationaux, EPA met à disposition des experts de chaque domaine qui transmettent leur savoir-faire et leurs expériences, pour garantir des formations professionnelles qualifiantes de qualité et orientées vers le développement de chaque apprenant.",
                "EPA_BURKINA is a professional training center specialized in IT and Humanitarian Action careers. Established in Burkina Faso for several years, EPA meets the growing need for qualified skills among NGOs, associations and businesses contributing to the country's development. Thanks to its team of national and international consultant trainers, EPA brings in experts from each field who share their know-how and experience, to guarantee quality professional training geared towards every learner's development.",
            ],
            'about_vision_title' => ['Vision', 'Vision'],
            'about_vision_text' => [
                "Devenir un acteur de référence en matière de formation innovante et technique dans les domaines de l'action humanitaire et de l'informatique sur le continent africain.",
                'To become a reference player in innovative, technical training in humanitarian action and IT across the African continent.',
            ],
            'about_mission_title' => ['Mission', 'Mission'],
            'about_mission_text' => [
                "Former à travers toute l'Afrique des jeunes professionnels compétents, éthiques et responsables, capables d'agir efficacement face aux enjeux majeurs du continent tout en contribuant activement à son développement durable.",
                "To train competent, ethical and responsible young professionals across Africa, able to effectively address the continent's major challenges while actively contributing to its sustainable development.",
            ],
            'about_antennes_title' => ['Nos antennes', 'Our centers'],

            'contact_title' => ['Contactez-nous', 'Contact us'],
            'contact_antennes_title' => ['Nos antennes', 'Our centers'],
            'contact_form_title' => ['Envoyez-nous un message', 'Send us a message'],

            'footer_tagline' => ['Former les acteurs du développement.', 'Training the changemakers.'],
            'footer_rights' => ['Tous droits réservés', 'All rights reserved'],
        ];

        foreach ($texts as $key => [$fr, $en]) {
            Setting::set($key.'_fr', $fr);
            Setting::set($key.'_en', $en);
        }
    }
}
