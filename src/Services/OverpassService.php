<?php
declare(strict_types=1);
namespace App\Services;
use App\Core\Database;

class OverpassService
{
    private string $endpoint = 'https://overpass-api.de/api/interpreter';

    public function fetchBusStops(array $bbox): array
    {
        [$s, $w, $n, $e] = $bbox;
        $query = "[out:json][timeout:30];node[\"highway\"=\"bus_stop\"]({$s},{$w},{$n},{$e});out body;";
        $ch = curl_init($this->endpoint);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => 'data=' . urlencode($query)]);
        $response = curl_exec($ch); curl_close($ch);
        $data = json_decode($response, true);
        return $data['elements'] ?? [];
    }

    public function importStopsForCity(int $cityId, array $bbox): int
    {
        $db = Database::getInstance();
        $elements = $this->fetchBusStops($bbox);
        $imported = 0;
        foreach ($elements as $el) {
            $name = $el['tags']['name'] ?? null;
            if (!$name) continue;
            $stmt = $db->prepare('INSERT IGNORE INTO stops (city_id, stop_name, latitude, longitude, osm_node_id) VALUES (?,?,?,?,?)');
            $stmt->execute([$cityId, $name, $el['lat'], $el['lon'], $el['id']]);
            if ($stmt->rowCount() > 0) $imported++;
        }
        return $imported;
    }
}
