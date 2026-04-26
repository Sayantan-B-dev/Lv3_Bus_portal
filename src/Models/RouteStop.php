<?php
/**
 * src/Models/RouteStop.php
 * Junction model for route_stops table.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class RouteStop
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** Add a stop to a route. */
    public function addStop(
        int $routeId,
        int $stopId,
        int $order,
        float $distKm = 0.0,
        ?int $offsetMins = null,
        bool $isMajor = false
    ): int {
        $stmt = $this->db->prepare(
            'INSERT INTO route_stops
             (route_id, stop_id, stop_order, distance_from_start_km, arrival_time_offset_mins, is_major_stop)
             VALUES (?,?,?,?,?,?)'
        );
        $stmt->execute([$routeId, $stopId, $order, $distKm, $offsetMins, (int)$isMajor]);
        return (int)$this->db->lastInsertId();
    }

    /** Get ordered stops for a route. */
    public function forRoute(int $routeId): array
    {
        $stmt = $this->db->prepare(
            'SELECT rs.*, s.stop_name, s.stop_code, s.latitude, s.longitude, s.landmark, s.is_terminal
             FROM route_stops rs
             JOIN stops s ON rs.stop_id = s.id
             WHERE rs.route_id = ?
             ORDER BY rs.stop_order ASC'
        );
        $stmt->execute([$routeId]);
        return $stmt->fetchAll();
    }

    /** Remove all stops from a route (for re-ordering). */
    public function clearRoute(int $routeId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM route_stops WHERE route_id = ?');
        return $stmt->execute([$routeId]);
    }

    /** Remove one specific stop from a route. */
    public function removeStop(int $routeId, int $stopId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM route_stops WHERE route_id = ? AND stop_id = ?'
        );
        return $stmt->execute([$routeId, $stopId]);
    }
}
