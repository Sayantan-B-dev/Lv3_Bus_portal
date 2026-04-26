<?php
/**
 * src/Services/DijkstraService.php
 * Dijkstra shortest-path between bus stops — SRS Section 7.7.
 */
declare(strict_types=1);
namespace App\Services;
use App\Core\Database;

class DijkstraService
{
    private \PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    /** Build bidirectional adjacency graph for a city. */
    private function buildGraph(int $cityId): array
    {
        $stmt = $this->db->prepare(
            'SELECT rs1.stop_id AS from_stop, rs2.stop_id AS to_stop,
             ABS(rs2.distance_from_start_km - rs1.distance_from_start_km) AS dist,
             rs1.route_id, r.route_number, r.route_type
             FROM route_stops rs1
             JOIN route_stops rs2 ON rs1.route_id = rs2.route_id
               AND rs2.stop_order = rs1.stop_order + 1
             JOIN routes r ON rs1.route_id = r.id
             WHERE r.city_id = ? AND r.is_active = 1'
        );
        $stmt->execute([$cityId]);
        $edges = $stmt->fetchAll();
        $graph = [];
        foreach ($edges as $e) {
            $edge = ['to' => $e['to_stop'], 'dist' => (float)$e['dist'], 'route_id' => $e['route_id'], 'route_number' => $e['route_number'], 'route_type' => $e['route_type']];
            $graph[$e['from_stop']][] = $edge;
            // Bidirectional
            $edge['to'] = $e['from_stop'];
            $graph[$e['to_stop']][] = $edge;
        }
        return $graph;
    }

    /** Run Dijkstra from fromStop to toStop. Returns path with legs, or null. */
    public function findShortestPath(int $cityId, int $fromStop, int $toStop): ?array
    {
        $graph = $this->buildGraph($cityId);
        $dist = $prev = $prevEdge = $visited = [];
        $queue = new \SplMinHeap();

        foreach (array_keys($graph) as $node) { $dist[$node] = PHP_FLOAT_MAX; }
        $dist[$fromStop] = 0.0;
        $queue->insert([0.0, $fromStop]);

        while (!$queue->isEmpty()) {
            [$d, $u] = $queue->extract();
            if (isset($visited[$u])) continue;
            $visited[$u] = true;
            if ($u === $toStop) break;
            foreach ($graph[$u] ?? [] as $edge) {
                $v = $edge['to'];
                $alt = $d + $edge['dist'];
                if ($alt < ($dist[$v] ?? PHP_FLOAT_MAX)) {
                    $dist[$v] = $alt;
                    $prev[$v] = $u;
                    $prevEdge[$v] = $edge;
                    $queue->insert([$alt, $v]);
                }
            }
        }

        if (!isset($prev[$toStop]) && $fromStop !== $toStop) return null;

        // Reconstruct path
        $path = []; $cur = $toStop;
        while ($cur !== $fromStop) {
            $path[] = ['stop' => $cur, 'edge' => $prevEdge[$cur]];
            $cur = $prev[$cur];
        }
        $path[] = ['stop' => $fromStop, 'edge' => null];
        $path = array_reverse($path);

        $legs = $this->groupIntoLegs($path, $cityId);
        return [
            'algorithm'            => 'dijkstra',
            'total_distance_km'    => round($dist[$toStop] ?? 0, 2),
            'estimated_time_mins'  => $this->estimateTime($dist[$toStop] ?? 0, count($legs)),
            'transfers'            => max(0, count($legs) - 1),
            'legs'                 => $legs,
        ];
    }

    private function groupIntoLegs(array $path, int $cityId): array
    {
        $legs = []; $currentRouteId = null; $legStops = [];
        foreach ($path as $step) {
            $edge = $step['edge'];
            if ($edge === null) { $legStops[] = $step['stop']; continue; }
            if ($currentRouteId !== $edge['route_id']) {
                if (!empty($legStops)) { $legs[] = $this->buildLeg($legStops, $currentRouteId); }
                $currentRouteId = $edge['route_id'];
                $legStops = [end($legStops) ?: $step['stop']];
            }
            $legStops[] = $step['stop'];
        }
        if (!empty($legStops) && $currentRouteId) { $legs[] = $this->buildLeg($legStops, $currentRouteId); }
        return $legs;
    }

    private function buildLeg(array $stopIds, int $routeId): array
    {
        $stopIds = array_unique(array_filter($stopIds));
        $stmt = $this->db->prepare('SELECT route_number, route_type FROM routes WHERE id = ?');
        $stmt->execute([$routeId]); $route = $stmt->fetch();
        $boardId = reset($stopIds); $alightId = end($stopIds);
        $stmt = $this->db->prepare('SELECT stop_name FROM stops WHERE id = ?');
        $stmt->execute([$boardId]); $board = $stmt->fetchColumn();
        $stmt->execute([$alightId]); $alight = $stmt->fetchColumn();
        return [
            'route_id' => $routeId,
            'route_number' => $route['route_number'] ?? '',
            'route_type'   => $route['route_type'] ?? '',
            'board_stop'   => $board,
            'alight_stop'  => $alight,
            'stops_count'  => count($stopIds),
        ];
    }

    private function estimateTime(float $distKm, int $legs): int
    {
        return (int)(($distKm / 20) * 60 + ($legs - 1) * 5);
    }
}
