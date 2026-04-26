<?php
/**
 * src/Models/City.php
 * City model — all database operations for the cities table.
 * Implements multi-city support as per SRS Section 7.6.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

class City
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ─── Read ─────────────────────────────────────────────────────────────────

    /** All active cities for the city selector dropdown. */
    public function allActive(): array
    {
        $stmt = $this->db->query(
            'SELECT id, city_name, state_region, country, country_code,
                    currency, timezone, center_lat, center_lng, default_zoom
             FROM cities
             WHERE is_active = 1
             ORDER BY city_name ASC'
        );
        return $stmt->fetchAll();
    }

    /** Find a city by primary key. */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM cities WHERE id = ? AND is_active = 1'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Find by name (case-insensitive). */
    public function findByName(string $name): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM cities WHERE LOWER(city_name) = LOWER(?) AND is_active = 1'
        );
        $stmt->execute([$name]);
        return $stmt->fetch() ?: null;
    }

    /** Count stats for a city: routes, stops. */
    public function getStats(int $cityId): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                (SELECT COUNT(*) FROM routes WHERE city_id = ? AND is_active = 1) AS route_count,
                (SELECT COUNT(*) FROM stops  WHERE city_id = ? AND is_active = 1) AS stop_count,
                (SELECT ROUND(AVG(frequency_mins),0) FROM routes WHERE city_id = ? AND is_active = 1) AS avg_frequency'
        );
        $stmt->execute([$cityId, $cityId, $cityId]);
        return $stmt->fetch() ?: ['route_count' => 0, 'stop_count' => 0, 'avg_frequency' => 0];
    }

    /** Global portal stats (all cities). */
    public function globalStats(): array
    {
        $stmt = $this->db->query(
            'SELECT
                (SELECT COUNT(*) FROM routes WHERE is_active = 1) AS total_routes,
                (SELECT COUNT(*) FROM stops  WHERE is_active = 1) AS total_stops,
                (SELECT COUNT(*) FROM cities WHERE is_active = 1) AS total_cities'
        );
        return $stmt->fetch() ?: [];
    }

    // ─── Write ────────────────────────────────────────────────────────────────

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO cities
             (city_name, state_region, country, country_code, currency, timezone,
              center_lat, center_lng, default_zoom, osm_relation_id)
             VALUES (?,?,?,?,?,?,?,?,?,?)'
        );
        $stmt->execute([
            $data['city_name'],
            $data['state_region'] ?? null,
            $data['country']      ?? 'India',
            $data['country_code'] ?? 'IN',
            $data['currency']     ?? 'INR',
            $data['timezone']     ?? 'Asia/Kolkata',
            $data['center_lat'],
            $data['center_lng'],
            $data['default_zoom'] ?? 12,
            $data['osm_relation_id'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE cities SET
             city_name=?, state_region=?, country=?, country_code=?,
             currency=?, timezone=?, center_lat=?, center_lng=?,
             default_zoom=?, osm_relation_id=?
             WHERE id = ?'
        );
        return $stmt->execute([
            $data['city_name'],
            $data['state_region'] ?? null,
            $data['country']      ?? 'India',
            $data['country_code'] ?? 'IN',
            $data['currency']     ?? 'INR',
            $data['timezone']     ?? 'Asia/Kolkata',
            $data['center_lat'],
            $data['center_lng'],
            $data['default_zoom'] ?? 12,
            $data['osm_relation_id'] ?? null,
            $id,
        ]);
    }

    public function setActive(int $id, bool $active): bool
    {
        $stmt = $this->db->prepare('UPDATE cities SET is_active = ? WHERE id = ?');
        return $stmt->execute([(int)$active, $id]);
    }

    /** Get default/active city (first active, or id=1). */
    public function getDefault(): ?array
    {
        $stmt = $this->db->query(
            'SELECT * FROM cities WHERE is_active = 1 ORDER BY id ASC LIMIT 1'
        );
        return $stmt->fetch() ?: null;
    }
}
