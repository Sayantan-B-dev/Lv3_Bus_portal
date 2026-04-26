<?php
/**
 * src/Models/Route.php
 * Route model — as specified in SRS Section 7.6 with multi-city support.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Route
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ─── Read ─────────────────────────────────────────────────────────────────

    /**
     * Get all active routes for a city with optional filters and pagination.
     */
    public function allByCity(int $cityId, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $sql    = 'SELECT * FROM routes WHERE city_id = ? AND is_active = 1';
        $params = [$cityId];

        if (!empty($filters['type'])) {
            $sql     .= ' AND route_type = ?';
            $params[] = $filters['type'];
        }
        if (!empty($filters['search'])) {
            $like     = '%' . $filters['search'] . '%';
            $sql     .= ' AND (route_number LIKE ? OR source LIKE ? OR destination LIKE ?)';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $sql .= ' ORDER BY route_number ASC';

        $offset   = ($page - 1) * $perPage;
        $sql     .= ' LIMIT ? OFFSET ?';
        $params[] = $perPage;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Count total routes for a city (for pagination).
     */
    public function countByCity(int $cityId, array $filters = []): int
    {
        $sql    = 'SELECT COUNT(*) FROM routes WHERE city_id = ? AND is_active = 1';
        $params = [$cityId];

        if (!empty($filters['type'])) {
            $sql     .= ' AND route_type = ?';
            $params[] = $filters['type'];
        }
        if (!empty($filters['search'])) {
            $like     = '%' . $filters['search'] . '%';
            $sql     .= ' AND (route_number LIKE ? OR source LIKE ? OR destination LIKE ?)';
            $params[] = $like; $params[] = $like; $params[] = $like;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /** Find a single route by ID. */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM routes WHERE id = ? AND is_active = 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Find route with full stops + fares data (for detail page & API).
     * Implements SRS Section 7.6 findWithStops().
     */
    public function findWithStops(int $id): ?array
    {
        $route = $this->findById($id);
        if (!$route) return null;

        $stmt = $this->db->prepare(
            'SELECT s.id, s.stop_name, s.stop_code, s.latitude, s.longitude,
                    s.landmark, s.zone, s.is_terminal,
                    rs.stop_order, rs.distance_from_start_km,
                    rs.arrival_time_offset_mins, rs.is_major_stop
             FROM route_stops rs
             JOIN stops s ON rs.stop_id = s.id
             WHERE rs.route_id = ?
             ORDER BY rs.stop_order ASC'
        );
        $stmt->execute([$id]);
        $route['stops'] = $stmt->fetchAll();
        $route['fares'] = $this->getFares($id);
        return $route;
    }

    /**
     * Full-text search across routes + stop names for a city.
     * Implements SRS Section 7.6 search().
     */
    public function search(string $query, int $cityId): array
    {
        $like = '%' . $query . '%';
        $stmt = $this->db->prepare(
            'SELECT DISTINCT r.* FROM routes r
             LEFT JOIN route_stops rs ON r.id = rs.route_id
             LEFT JOIN stops s ON rs.stop_id = s.id
             WHERE r.city_id = ? AND r.is_active = 1 AND (
               r.route_number LIKE ? OR r.source LIKE ? OR
               r.destination LIKE ? OR s.stop_name LIKE ?)
             ORDER BY r.route_number ASC LIMIT 20'
        );
        $stmt->execute([$cityId, $like, $like, $like, $like]);
        return $stmt->fetchAll();
    }

    /** Get fares for a route. */
    public function getFares(int $routeId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM fares WHERE route_id = ? ORDER BY passenger_type, min_km'
        );
        $stmt->execute([$routeId]);
        return $stmt->fetchAll();
    }

    /** Get all routes passing through a specific stop. */
    public function byStop(int $stopId): array
    {
        $stmt = $this->db->prepare(
            'SELECT r.* FROM routes r
             JOIN route_stops rs ON r.id = rs.route_id
             WHERE rs.stop_id = ? AND r.is_active = 1
             ORDER BY r.route_number ASC'
        );
        $stmt->execute([$stopId]);
        return $stmt->fetchAll();
    }

    // ─── Write ────────────────────────────────────────────────────────────────

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO routes
             (city_id, route_number, source, destination, route_type,
              frequency_mins, first_bus_time, last_bus_time, total_distance_km, description)
             VALUES (?,?,?,?,?,?,?,?,?,?)'
        );
        $stmt->execute([
            $data['city_id'],
            $data['route_number'],
            $data['source'],
            $data['destination'],
            $data['route_type']       ?? 'Normal',
            $data['frequency_mins']   ?? 20,
            $data['first_bus_time'],
            $data['last_bus_time'],
            $data['total_distance_km'] ?? 0.00,
            $data['description']       ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE routes SET
             route_number=?, source=?, destination=?, route_type=?,
             frequency_mins=?, first_bus_time=?, last_bus_time=?,
             total_distance_km=?, description=?
             WHERE id = ?'
        );
        return $stmt->execute([
            $data['route_number'],
            $data['source'],
            $data['destination'],
            $data['route_type']        ?? 'Normal',
            $data['frequency_mins']    ?? 20,
            $data['first_bus_time'],
            $data['last_bus_time'],
            $data['total_distance_km'] ?? 0.00,
            $data['description']       ?? null,
            $id,
        ]);
    }

    public function softDelete(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE routes SET is_active = 0 WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
