<?php
declare(strict_types=1);
namespace App\Services;
use App\Core\Database;

class PlannerService
{
    private \PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    /**
     * Find routes connecting two stops. Tries direct first, then 1-transfer.
     */
    public function findRoutes(int $cityId, int $fromStopId, int $toStopId): array
    {
        // 1. Try Direct Routes
        $direct = $this->findDirect($cityId, $fromStopId, $toStopId);
        if (!empty($direct)) {
            return ['type' => 'direct', 'results' => $direct];
        }

        // 2. Try 1-Transfer Routes
        $transfers = $this->findWithTransfer($cityId, $fromStopId, $toStopId);
        if (!empty($transfers)) {
            return ['type' => 'transfer', 'results' => $transfers];
        }

        return [];
    }

    private function findDirect(int $cityId, int $fromStopId, int $toStopId): array
    {
        $sql = "SELECT r.*, 
                       rs1.stop_order as start_order, 
                       rs2.stop_order as end_order,
                       ABS(rs2.distance_from_start_km - rs1.distance_from_start_km) as distance
                FROM routes r
                JOIN route_stops rs1 ON r.id = rs1.route_id AND rs1.stop_id = ?
                JOIN route_stops rs2 ON r.id = rs2.route_id AND rs2.stop_id = ?
                WHERE r.city_id = ? AND r.is_active = 1 AND rs1.stop_order < rs2.stop_order
                ORDER BY distance ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fromStopId, $toStopId, $cityId]);
        $routes = $stmt->fetchAll();

        $results = [];
        foreach ($routes as $route) {
            $results[] = [
                'route_id' => $route['id'],
                'route_number' => $route['route_number'],
                'route_name' => $route['source'] . ' - ' . $route['destination'],
                'route_type' => $route['route_type'],
                'distance_km' => round((float)$route['distance'], 2),
            ];
        }
        return $results;
    }

    private function findWithTransfer(int $cityId, int $fromStopId, int $toStopId): array
    {
        // Find routes passing through FromStop and their subsequent stops
        // Find routes passing through ToStop and their previous stops
        // Find common stops (Transfer points)
        $sql = "SELECT 
                    r1.id as r1_id, r1.route_number as r1_num, r1.source as r1_src, r1.destination as r1_dest,
                    r2.id as r2_id, r2.route_number as r2_num, r2.source as r2_src, r2.destination as r2_dest,
                    s.id as transfer_stop_id, s.stop_name as transfer_stop_name,
                    (ABS(rs1_end.distance_from_start_km - rs1_start.distance_from_start_km) + 
                     ABS(rs2_end.distance_from_start_km - rs2_start.distance_from_start_km)) as total_dist
                FROM route_stops rs1_start
                JOIN routes r1 ON rs1_start.route_id = r1.id
                JOIN route_stops rs1_end ON r1.id = rs1_end.route_id
                
                JOIN route_stops rs2_start ON rs1_end.stop_id = rs2_start.stop_id
                JOIN routes r2 ON rs2_start.route_id = r2.id
                JOIN route_stops rs2_end ON r2.id = rs2_end.route_id
                
                JOIN stops s ON rs1_end.stop_id = s.id
                
                WHERE r1.city_id = ? AND r2.city_id = ?
                  AND rs1_start.stop_id = ? 
                  AND rs2_end.stop_id = ?
                  AND rs1_start.stop_order < rs1_end.stop_order
                  AND rs2_start.stop_order < rs2_end.stop_order
                  AND r1.id != r2.id
                ORDER BY total_dist ASC
                LIMIT 5";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cityId, $cityId, $fromStopId, $toStopId]);
        $rows = $stmt->fetchAll();

        $results = [];
        foreach ($rows as $row) {
            $row['r1_name'] = $row['r1_src'] . ' - ' . $row['r1_dest'];
            $row['r2_name'] = $row['r2_src'] . ' - ' . $row['r2_dest'];
            $results[] = $row;
        }
        return $results;
    }
}
