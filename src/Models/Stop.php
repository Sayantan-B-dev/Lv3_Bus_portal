<?php
/**
 * src/Models/Stop.php
 * Stop model — bus stops per city.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Stop
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ─── Read ─────────────────────────────────────────────────────────────────

    /** All active stops for a city. */
    public function allByCity(int $cityId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM stops WHERE city_id = ? AND is_active = 1 ORDER BY stop_name ASC'
        );
        $stmt->execute([$cityId]);
        return $stmt->fetchAll();
    }

    /** Find stop by ID. */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM stops WHERE id = ? AND is_active = 1'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Find stop with all routes that pass through it.
     */
    public function findWithRoutes(int $id): ?array
    {
        $stop = $this->findById($id);
        if (!$stop) return null;

        $stmt = $this->db->prepare(
            'SELECT r.id, r.route_number, r.source, r.destination, r.route_type,
                    r.frequency_mins, r.first_bus_time, r.last_bus_time,
                    rs.stop_order, rs.distance_from_start_km, rs.is_major_stop
             FROM route_stops rs
             JOIN routes r ON rs.route_id = r.id
             WHERE rs.stop_id = ? AND r.is_active = 1
             ORDER BY r.route_number ASC'
        );
        $stmt->execute([$id]);
        $stop['routes'] = $stmt->fetchAll();
        return $stop;
    }

    /**
     * Find stops near a coordinate (within radius km).
     * Uses Haversine-approximation in SQL.
     */
    public function nearby(float $lat, float $lng, float $radiusKm = 1.0, int $cityId = 0): array
    {
        $sql = 'SELECT *,
                (6371 * ACOS(
                    COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                    COS(RADIANS(longitude) - RADIANS(?)) +
                    SIN(RADIANS(?)) * SIN(RADIANS(latitude))
                )) AS distance_km
                FROM stops
                WHERE is_active = 1
                    AND latitude IS NOT NULL';

        $params = [$lat, $lng, $lat];

        if ($cityId > 0) {
            $sql     .= ' AND city_id = ?';
            $params[] = $cityId;
        }

        $sql .= ' HAVING distance_km <= ? ORDER BY distance_km ASC LIMIT 20';
        $params[] = $radiusKm;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /** Autocomplete search on stop_name. */
    public function autocomplete(string $query, int $cityId, int $limit = 10): array
    {
        $like = '%' . $query . '%';
        $stmt = $this->db->prepare(
            'SELECT id, stop_name, stop_code, latitude, longitude, landmark
             FROM stops
             WHERE city_id = ? AND is_active = 1
               AND (stop_name LIKE ? OR stop_code LIKE ?)
             ORDER BY stop_name ASC LIMIT ?'
        );
        $stmt->execute([$cityId, $like, $like, $limit]);
        return $stmt->fetchAll();
    }

    // ─── Write ────────────────────────────────────────────────────────────────

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO stops
             (city_id, stop_name, stop_code, latitude, longitude, landmark, zone, osm_node_id, is_terminal)
             VALUES (?,?,?,?,?,?,?,?,?)'
        );
        $stmt->execute([
            $data['city_id'],
            $data['stop_name'],
            $data['stop_code']   ?? null,
            $data['latitude']    ?? null,
            $data['longitude']   ?? null,
            $data['landmark']    ?? null,
            $data['zone']        ?? null,
            $data['osm_node_id'] ?? null,
            $data['is_terminal'] ?? 0,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE stops SET
             stop_name=?, stop_code=?, latitude=?, longitude=?,
             landmark=?, zone=?, is_terminal=?
             WHERE id = ?'
        );
        return $stmt->execute([
            $data['stop_name'],
            $data['stop_code']   ?? null,
            $data['latitude']    ?? null,
            $data['longitude']   ?? null,
            $data['landmark']    ?? null,
            $data['zone']        ?? null,
            $data['is_terminal'] ?? 0,
            $id,
        ]);
    }

    public function softDelete(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE stops SET is_active = 0 WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
