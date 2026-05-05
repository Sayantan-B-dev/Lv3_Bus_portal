<?php
declare(strict_types=1);
namespace App\Models;
use App\Core\Database;

class User
{
    private \PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function findByGoogleId(string $googleId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE google_id = ?');
        $stmt->execute([$googleId]);
        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Resolve login identifier: exact email match (case-insensitive) or username match (collation-aware).
     */
    public function findByEmailOrUsername(string $identifier): ?array
    {
        $trimmed = trim($identifier);
        if ($trimmed === '') {
            return null;
        }
        $emailKey = strtolower($trimmed);
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE LOWER(email) = ? OR username = ? LIMIT 1'
        );
        $stmt->execute([$emailKey, $trimmed]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Create a password-based account (google_id NULL). Name defaults to username for NOT NULL name column.
     */
    public function createWithPassword(string $email, string $username, string $passwordHash, string $name): ?array
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (google_id, name, email, username, password_hash, role)
             VALUES (NULL, ?, ?, ?, ?, ?)'
        );
        try {
            $stmt->execute([$name, $email, $username, $passwordHash, 'viewer']);
        } catch (\PDOException $e) {
            return null;
        }
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findOrCreate(array $googleUser): array
    {
        $existing = $this->findByGoogleId($googleUser['sub']);
        if ($existing) {
            $this->updateLastLogin($existing['id']);
            return $existing;
        }
        // Check by email (seeded admin)
        $byEmail = $this->findByEmail($googleUser['email']);
        if ($byEmail) {
            $this->db->prepare('UPDATE users SET google_id=?, avatar_url=? WHERE id=?')
                ->execute([$googleUser['sub'], $googleUser['picture'] ?? null, $byEmail['id']]);
            $this->updateLastLogin($byEmail['id']);
            return $this->findById($byEmail['id']);
        }

        $stmt = $this->db->prepare(
            'INSERT INTO users (google_id, name, email, avatar_url, role) VALUES (?,?,?,?,?)'
        );
        $stmt->execute([
            $googleUser['sub'],
            $googleUser['name'],
            $googleUser['email'],
            $googleUser['picture'] ?? null,
            'viewer',
        ]);
        return $this->findById((int)$this->db->lastInsertId());
    }

    public function updateLastLogin(int $id): void
    {
        $this->db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?')->execute([$id]);
    }

    public function updatePasswordHash(int $id, string $passwordHash): void
    {
        $this->db->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([$passwordHash, $id]);
    }

    public function updateProfile(int $id, array $data): bool
    {
        $fields = [];
        $params = [];
        
        $allowedFields = [
            'name', 'username', 'phone', 'alternate_phone', 'dob', 'gender', 
            'address', 'city', 'state', 'country', 'pincode', 'bio', 
            'profile_image', 'cover_image', 'occupation', 'is_student', 
            'college_name', 'college_registration_number', 'roll_number', 
            'branch', 'year_of_study', 'semester', 'graduation_year', 'linkedin_url', 
            'github_url', 'portfolio_url', 'skills', 'emergency_contact_name', 
            'emergency_contact_phone', 'latitude', 'longitude'
        ];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $fields[] = "`$key` = ?";
                $params[] = $value;
            }
        }

        if (empty($fields)) return false;

        $params[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . ", last_profile_updated_at = NOW() WHERE id = ?";
        return $this->db->prepare($sql)->execute($params);
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();
    }

    public function setActive(int $id, bool $active): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET is_active=? WHERE id=?');
        return $stmt->execute([(int)$active, $id]);
    }

    public function log(int $userId, string $action, string $entityType = null, int $entityId = null, array $details = [], string $ip = ''): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO activity_log (user_id, action, entity_type, entity_id, details, ip_address)
             VALUES (?,?,?,?,?,?)'
        );
        $stmt->execute([
            $userId, $action, $entityType, $entityId,
            !empty($details) ? json_encode($details) : null,
            $ip,
        ]);
    }
}
