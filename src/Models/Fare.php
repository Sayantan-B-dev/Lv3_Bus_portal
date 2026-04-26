<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Database;

class Fare
{
    private \PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function forRoute(int $routeId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM fares WHERE route_id = ? ORDER BY passenger_type, min_km');
        $stmt->execute([$routeId]);
        return $stmt->fetchAll();
    }

    public function calculate(int $routeId, float $distKm, string $passengerType = 'General'): ?float
    {
        $stmt = $this->db->prepare(
            'SELECT fare_amount FROM fares WHERE route_id=? AND passenger_type=? AND min_km<=? AND max_km>=? LIMIT 1'
        );
        $stmt->execute([$routeId, $passengerType, $distKm, $distKm]);
        $fare = $stmt->fetchColumn();
        return ($fare !== false) ? (float)$fare : null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO fares (route_id, min_km, max_km, fare_amount, passenger_type) VALUES (?,?,?,?,?)'
        );
        $stmt->execute([$data['route_id'], $data['min_km'], $data['max_km'], $data['fare_amount'], $data['passenger_type'] ?? 'General']);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE fares SET min_km=?, max_km=?, fare_amount=?, passenger_type=? WHERE id=?');
        return $stmt->execute([$data['min_km'], $data['max_km'], $data['fare_amount'], $data['passenger_type'] ?? 'General', $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM fares WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function deleteForRoute(int $routeId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM fares WHERE route_id = ?');
        return $stmt->execute([$routeId]);
    }
}
