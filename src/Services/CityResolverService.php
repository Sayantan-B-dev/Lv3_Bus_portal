<?php
declare(strict_types=1);
namespace App\Services;

class CityResolverService
{
    public function geocodeCity(string $cityName, string $countryCode = ''): ?array
    {
        $q   = urlencode($cityName . ($countryCode ? ", {$countryCode}" : ''));
        $url = "https://nominatim.openstreetmap.org/search?q={$q}&format=json&limit=1";
        $ch  = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ['User-Agent: BusPortal/2.0 (contact@busportal.in)']]);
        $response = curl_exec($ch); curl_close($ch);
        $results  = json_decode($response, true);
        if (empty($results)) return null;
        return ['lat' => (float)$results[0]['lat'], 'lng' => (float)$results[0]['lon'], 'display_name' => $results[0]['display_name'], 'osm_id' => $results[0]['osm_id']];
    }

    public function getBoundingBox(float $lat, float $lng, float $radiusKm = 15): array
    {
        $delta = $radiusKm / 111.0;
        return [$lat - $delta, $lng - $delta, $lat + $delta, $lng + $delta];
    }
}
