<?php
declare(strict_types=1);
namespace App\Services;

/**
 * RoutingService — wraps OpenRouteService API for turn-by-turn routing.
 * Free tier: 2000 req/day. Key stored in ORS_API_KEY .env variable.
 */
class RoutingService
{
    private string $apiKey;
    private string $baseUrl = 'https://api.openrouteservice.org/v2';

    public function __construct()
    {
        $this->apiKey = $_ENV['ORS_API_KEY'] ?? '';
    }

    /**
     * Get driving/walking directions between two coordinates.
     * Returns GeoJSON FeatureCollection or null on failure.
     */
    public function getDirections(float $fromLat, float $fromLng, float $toLat, float $toLng, string $profile = 'driving-car'): ?array
    {
        if (!$this->apiKey) return null;

        $url     = "{$this->baseUrl}/directions/{$profile}/geojson";
        $payload = json_encode(['coordinates' => [[$fromLng, $fromLat], [$toLng, $toLat]]]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: ' . $this->apiKey,
            ],
        ]);
        $response = curl_exec($ch);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200) return null;
        return json_decode($response, true);
    }
}
