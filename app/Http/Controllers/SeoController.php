<?php

namespace App\Http\Controllers;

use App\Models\Actualite;
use App\Models\Formation;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $content = "User-agent: *\nDisallow:\n\nSitemap: ".url('/sitemap.xml')."\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }

    public function sitemap(): Response
    {
        $urls = [
            ['loc' => route('home'), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => route('about'), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => route('formations.index'), 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => route('actualites.index'), 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => route('contact'), 'changefreq' => 'yearly', 'priority' => '0.5'],
            ['loc' => route('candidatures.create'), 'changefreq' => 'monthly', 'priority' => '0.8'],
        ];

        Formation::where('published', true)->get(['slug', 'updated_at'])->each(function (Formation $formation) use (&$urls) {
            $urls[] = [
                'loc' => route('formations.show', $formation),
                'lastmod' => $formation->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ];
        });

        Actualite::whereNotNull('published_at')->get(['slug', 'updated_at'])->each(function (Actualite $actualite) use (&$urls) {
            $urls[] = [
                'loc' => route('actualites.show', $actualite),
                'lastmod' => $actualite->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        });

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
