<?php
declare(strict_types=1);
namespace App\Services;
use App\Core\Database;

/**
 * GtfsImportService — parses a GTFS feed (stops.txt, routes.txt, stop_times.txt)
 * and imports the data into the yatrapath database for a given city.
 *
 * Usage:
 *   $service = new GtfsImportService();
 *   $result  = $service->importFromDirectory($cityId, '/path/to/gtfs/');
 */
class GtfsImportService
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Import stops.txt from a GTFS directory into the stops table.
     * Returns number of stops imported.
     */
    public function importStops(int $cityId, string $gtfsDir): int
    {
        $file = rtrim($gtfsDir, '/') . '/stops.txt';
        if (!file_exists($file)) return 0;

        $handle  = fopen($file, 'r');
        $headers = array_map('trim', fgetcsv($handle));
        $count   = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);
            $stmt = $this->db->prepare(
                'INSERT IGNORE INTO stops (city_id, stop_name, stop_code, latitude, longitude)
                 VALUES (?,?,?,?,?)'
            );
            $stmt->execute([
                $cityId,
                $data['stop_name'] ?? '',
                $data['stop_code'] ?? null,
                $data['stop_lat']  ?? null,
                $data['stop_lon']  ?? null,
            ]);
            if ($stmt->rowCount() > 0) $count++;
        }
        fclose($handle);
        return $count;
    }

    /**
     * Import routes.txt into the routes table.
     */
    public function importRoutes(int $cityId, string $gtfsDir): int
    {
        $file = rtrim($gtfsDir, '/') . '/routes.txt';
        if (!file_exists($file)) return 0;

        $handle  = fopen($file, 'r');
        $headers = array_map('trim', fgetcsv($handle));
        $count   = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);
            $stmt = $this->db->prepare(
                'INSERT IGNORE INTO routes
                 (city_id, route_number, source, destination, route_type, frequency_mins, first_bus_time, last_bus_time)
                 VALUES (?,?,?,?,?,?,?,?)'
            );
            $stmt->execute([
                $cityId,
                $data['route_short_name'] ?? $data['route_id'],
                $data['route_long_name'] ?? 'Unknown',
                $data['route_long_name'] ?? 'Unknown',
                'Normal', 20, '05:00:00', '22:00:00',
            ]);
            if ($stmt->rowCount() > 0) $count++;
        }
        fclose($handle);
        return $count;
    }

    public function importFromDirectory(int $cityId, string $gtfsDir): array
    {
        return [
            'stops_imported'  => $this->importStops($cityId, $gtfsDir),
            'routes_imported' => $this->importRoutes($cityId, $gtfsDir),
        ];
    }
}
