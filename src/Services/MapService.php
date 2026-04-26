<?php
declare(strict_types=1);
namespace App\Services;

/**
 * MapService — helper for building Leaflet.js map configuration from DB data.
 * Actual rendering is done client-side; this generates the PHP-to-JS data payload.
 */
class MapService
{
    /**
     * Build a JSON-serializable config array for the Leaflet map.
     * Passed to the view as $mapConfig, then json_encoded into a <script> tag.
     */
    public function buildRouteMapConfig(array $route): array
    {
        $stops  = $route['stops'] ?? [];
        $coords = array_map(fn($s) => ['lat' => (float)$s['latitude'], 'lng' => (float)$s['longitude'], 'name' => $s['stop_name'], 'is_major' => (bool)$s['is_major_stop'], 'is_terminal' => (bool)$s['is_terminal']], array_filter($stops, fn($s) => $s['latitude'] && $s['longitude']));

        // Center on first stop with coords
        $center = $coords[0] ?? ['lat' => 22.5726, 'lng' => 88.3639];

        return [
            'center'      => $center,
            'zoom'        => 13,
            'stops'       => array_values($coords),
            'route_type'  => $route['route_type'] ?? 'Normal',
            'route_number'=> $route['route_number'] ?? '',
        ];
    }

    /**
     * Build map config for the city overview (home page).
     */
    public function buildCityMapConfig(array $city): array
    {
        return [
            'center' => ['lat' => (float)$city['center_lat'], 'lng' => (float)$city['center_lng']],
            'zoom'   => (int)($city['default_zoom'] ?? 12),
        ];
    }
}
