<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Bilingual content fields, grouped for the admin form.
     * key => [label, input type ('text'|'textarea')]
     */
    public const TEXT_GROUPS = [
        'En-tête & navigation' => [
            'nav_home' => ['Lien "Accueil"', 'text'],
            'nav_about' => ['Lien "Qui sommes-nous"', 'text'],
            'nav_formations' => ['Lien "Nos formations"', 'text'],
            'nav_actualites' => ['Lien "Actualités"', 'text'],
            'nav_contact' => ['Lien "Contact"', 'text'],
            'nav_cta' => ['Bouton "S\'inscrire" (en-tête)', 'text'],
            'header_tagline_line1' => ['Sous-titre logo — ligne 1', 'text'],
            'header_tagline_line2' => ['Sous-titre logo — ligne 2', 'text'],
        ],
        'Accueil — Bannière' => [
            'hero_kicker' => ['Accroche (au-dessus du titre)', 'text'],
            'hero_title' => ['Titre principal', 'textarea'],
            'hero_subtitle' => ['Texte sous le titre', 'textarea'],
            'hero_cta_primary' => ['Bouton principal', 'text'],
            'hero_cta_secondary' => ['Bouton secondaire', 'text'],
        ],
        'Accueil — Sections' => [
            'home_programmes_title' => ['Titre "Nos programmes"', 'text'],
            'home_programmes_subtitle' => ['Sous-titre "Nos programmes" (utiliser {count} pour le nombre de formations)', 'text'],
            'home_advantage_1' => ['Avantage 1', 'text'],
            'home_advantage_2' => ['Avantage 2', 'text'],
            'home_advantage_3' => ['Avantage 3', 'text'],
            'home_advantage_4' => ['Avantage 4', 'text'],
            'home_antennes_title' => ['Titre "Nos antennes"', 'text'],
            'home_actualites_title' => ['Titre "Actualités"', 'text'],
            'home_actualites_link' => ['Lien "Voir toutes les actualités"', 'text'],
            'home_partenaires_title' => ['Titre "Nos partenaires"', 'text'],
            'home_partenaires_subtitle' => ['Sous-titre "Nos partenaires"', 'text'],
            'home_cta_title' => ['Titre bandeau final', 'text'],
            'home_cta_subtitle' => ['Sous-titre bandeau final', 'text'],
            'home_cta_button' => ['Bouton bandeau final', 'text'],
        ],
        'Qui sommes-nous' => [
            'about_title' => ['Titre de page', 'text'],
            'about_intro' => ['Texte de présentation', 'textarea'],
            'about_vision_title' => ['Titre "Vision"', 'text'],
            'about_vision_text' => ['Texte "Vision"', 'textarea'],
            'about_mission_title' => ['Titre "Mission"', 'text'],
            'about_mission_text' => ['Texte "Mission"', 'textarea'],
            'about_antennes_title' => ['Titre "Nos antennes"', 'text'],
        ],
        'Contact' => [
            'contact_title' => ['Titre de page', 'text'],
            'contact_antennes_title' => ['Titre "Nos antennes"', 'text'],
            'contact_form_title' => ['Titre du formulaire', 'text'],
        ],
        'Pied de page' => [
            'footer_tagline' => ['Accroche sous le logo', 'text'],
            'footer_rights' => ['Mention "Tous droits réservés"', 'text'],
        ],
    ];

    public function edit(): View
    {
        $values = [];

        foreach (self::TEXT_GROUPS as $fields) {
            foreach (array_keys($fields) as $key) {
                $values[$key.'_fr'] = Setting::get($key.'_fr');
                $values[$key.'_en'] = Setting::get($key.'_en');
            }
        }

        return view('admin.settings.edit', [
            'groups' => self::TEXT_GROUPS,
            'values' => $values,
            'heroSlideDelay' => Setting::get('hero_slide_delay_seconds', '4.5'),
            'logoUrl' => Setting::logoUrl(),
            'chatAssistantName' => Setting::get('chat_assistant_name', 'Aïcha'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [
            'hero_slide_delay_seconds' => ['required', 'numeric', 'min:1', 'max:30'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'chat_assistant_name' => ['required', 'string', 'max:50'],
        ];

        foreach (self::TEXT_GROUPS as $fields) {
            foreach (array_keys($fields) as $key) {
                $rules[$key.'_fr'] = ['nullable', 'string'];
                $rules[$key.'_en'] = ['nullable', 'string'];
            }
        }

        $data = $request->validate($rules);

        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('site_logo');
            Setting::set('site_logo', $request->file('logo')->store('branding', 'public'));
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
        }
        unset($data['logo']);

        Setting::set('hero_slide_delay_seconds', (string) $data['hero_slide_delay_seconds']);
        unset($data['hero_slide_delay_seconds']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Réglages enregistrés.');
    }
}
