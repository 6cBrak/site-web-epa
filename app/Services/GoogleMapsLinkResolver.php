<?php

namespace App\Services;

class GoogleMapsLinkResolver
{
    /**
     * Résout un lien Google Maps (court ou long, partagé depuis l'app/le site Google Maps)
     * et en extrait les coordonnées GPS, si possible.
     *
     * @return array{lat: float, lng: float}|null
     */
    public function resolveCoordinates(string $url): ?array
    {
        $finalUrl = $this->followRedirects($url) ?? $url;

        // Formats rencontrés dans les URLs Google Maps, du plus au moins précis.
        $patterns = [
            '/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/',   // pin précis d'un lieu (data=...!3d..!4d..)
            '/[?&]q=(-?\d+\.\d+),[\s+]*(-?\d+\.\d+)/', // ?q=lat,lng (le "+" devient un espace après urldecode)
            '/@(-?\d+\.\d+),(-?\d+\.\d+)/',        // centre de carte @lat,lng,zoom
            '#/search/(-?\d+\.\d+),[\s+]*(-?\d+\.\d+)#', // /search/lat,+lng
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, urldecode($finalUrl), $matches)) {
                return ['lat' => (float) $matches[1], 'lng' => (float) $matches[2]];
            }
        }

        return null;
    }

    protected function followRedirects(string $url): ?string
    {
        if (! function_exists('curl_init')) {
            return null;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_NOBODY => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; EPA-BURKINA-site)',
        ]);
        curl_exec($ch);
        $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $error = curl_errno($ch);
        curl_close($ch);

        return $error ? null : ($effectiveUrl ?: null);
    }
}
