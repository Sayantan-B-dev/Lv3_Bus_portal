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
        
        // 2. Try 1-Transfer Routes (Always try even if direct exists, to give options, 
        // but the SRS says try direct first. Let's return both if available or prioritize)
        $transfers = $this->findWithTransfer($cityId, $fromStopId, $toStopId);

        if (empty($direct) && empty($transfers)) {
            return [];
        }

        return [
            'type' => !empty($direct) ? 'direct' : 'transfer',
            'results' => !empty($direct) ? $direct : $transfers,
            'has_direct' => !empty($direct),
            'has_transfer' => !empty($transfers)
        ];
    }

    private function findDirect(int $cityId, int $fromStopId, int $toStopId): array
    {
        $sql = "SELECT r.*, 
                       rs1.stop_order as start_order, 
                       rs2.stop_order as end_order,
                       rs1.distance_from_start_km as dist1,
                       rs2.distance_from_start_km as dist2,
                       s1.stop_name as from_stop_name,
                       s2.stop_name as to_stop_name
                FROM routes r
                JOIN route_stops rs1 ON r.id = rs1.route_id AND rs1.stop_id = ?
                JOIN route_stops rs2 ON r.id = rs2.route_id AND rs2.stop_id = ?
                JOIN stops s1 ON rs1.stop_id = s1.id
                JOIN stops s2 ON rs2.stop_id = s2.id
                WHERE r.city_id = ? AND r.is_active = 1 AND rs1.stop_order < rs2.stop_order
                ORDER BY ABS(rs2.distance_from_start_km - rs1.distance_from_start_km) ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fromStopId, $toStopId, $cityId]);
        $routes = $stmt->fetchAll();

        $results = [];
        foreach ($routes as $route) {
            $dist = abs((float)$route['dist2'] - (float)$route['dist1']);
            $results[] = [
                'route_id' => $route['id'],
                'route_number' => $route['route_number'],
                'route_name' => $route['source'] . ' - ' . $route['destination'],
                'from_stop' => $route['from_stop_name'],
                'to_stop' => $route['to_stop_name'],
                'route_type' => $route['route_type'],
                'distance_km' => round($dist, 2),
            ];
        }
        return $results;
    }

    private function findWithTransfer(int $cityId, int $fromStopId, int $toStopId): array
    {
        $sql = "SELECT 
                    r1.id as r1_id, r1.route_number as r1_num, r1.source as r1_src, r1.destination as r1_dest,
                    r2.id as r2_id, r2.route_number as r2_num, r2.source as r2_src, r2.destination as r2_dest,
                    s_mid.id as transfer_stop_id, s_mid.stop_name as transfer_stop_name,
                    s_start.stop_name as from_stop_name, s_end.stop_name as to_stop_name,
                    ABS(rs1_end.distance_from_start_km - rs1_start.distance_from_start_km) as dist_leg1,
                    ABS(rs2_end.distance_from_start_km - rs2_start.distance_from_start_km) as dist_leg2
                FROM route_stops rs1_start
                JOIN routes r1 ON rs1_start.route_id = r1.id
                JOIN route_stops rs1_end ON r1.id = rs1_end.route_id
                
                JOIN route_stops rs2_start ON rs1_end.stop_id = rs2_start.stop_id
                JOIN routes r2 ON rs2_start.route_id = r2.id
                JOIN route_stops rs2_end ON r2.id = rs2_end.route_id
                
                JOIN stops s_start ON rs1_start.stop_id = s_start.id
                JOIN stops s_mid ON rs1_end.stop_id = s_mid.id
                JOIN stops s_end ON rs2_end.stop_id = s_end.id
                
                WHERE r1.city_id = ? AND r2.city_id = ?
                  AND r1.is_active = 1 AND r2.is_active = 1
                  AND rs1_start.stop_id = ? 
                  AND rs2_end.stop_id = ?
                  AND rs1_start.stop_order < rs1_end.stop_order
                  AND rs2_start.stop_order < rs2_end.stop_order
                  AND r1.id != r2.id
                ORDER BY (dist_leg1 + dist_leg2) ASC
                LIMIT 5";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cityId, $cityId, $fromStopId, $toStopId]);
        $rows = $stmt->fetchAll();

        $results = [];
        foreach ($rows as $row) {
            $row['total_dist'] = round((float)$row['dist_leg1'] + (float)$row['dist_leg2'], 2);
            $row['r1_name'] = $row['r1_src'] . ' - ' . $row['r1_dest'];
            $row['r2_name'] = $row['r2_src'] . ' - ' . $row['r2_dest'];
            $results[] = $row;
        }
        return $results;
    }
}
