<?php
/**
 * src/Services/AuthService.php
 * Google OAuth 2.0 + JWT — SRS Section 7.3.
 */
declare(strict_types=1);
namespace App\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class AuthService
{
    private const PASSWORD_MIN_LENGTH = 8;

    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;
    private User $users;

    public function __construct()
    {
        $this->clientId     = $_ENV['GOOGLE_CLIENT_ID']     ?? '';
        $this->clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? '';
        $this->redirectUri  = $_ENV['GOOGLE_REDIRECT_URI']  ?? '';
        $this->users        = new User();
    }

    public function getGoogleAuthUrl(): string
    {
        $params = http_build_query([
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $this->generateState(),
            'access_type'   => 'offline',
            'prompt'        => 'consent',
        ]);
        return 'https://accounts.google.com/o/oauth2/auth?' . $params;
    }

    public function exchangeCode(string $code): array
    {
        $response = $this->httpPost('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code',
        ]);
        return json_decode($response, true) ?? [];
    }

    public function getGoogleUser(string $accessToken): array
    {
        $ch = curl_init('https://www.googleapis.com/oauth2/v3/userinfo');
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken]]);
        $result = curl_exec($ch); curl_close($ch);
        return json_decode($result, true) ?? [];
    }

    public function findOrCreateUser(array $googleUser): array
    {
        return $this->users->findOrCreate($googleUser);
    }

    /**
     * @return array{ok:true,user:array}|array{ok:false,error:string}
     */
    public function registerLocalUser(string $email, string $username, string $password, ?string $name = null): array
    {
        $email = strtolower(trim($email));
        $username = trim($username);
        $name = $name !== null ? trim($name) : '';

        if ($email === '' || $username === '' || $password === '') {
            return ['ok' => false, 'error' => 'Email, username, and password are required.'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'error' => 'Please enter a valid email address.'];
        }
        if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
            return ['ok' => false, 'error' => 'Username must be 3–50 characters (letters, numbers, underscore only).'];
        }
        if (strlen($password) < self::PASSWORD_MIN_LENGTH) {
            return ['ok' => false, 'error' => 'Password must be at least ' . self::PASSWORD_MIN_LENGTH . ' characters.'];
        }

        if ($this->users->findByEmail($email)) {
            return ['ok' => false, 'error' => 'An account with this email already exists. Try signing in with Google or email.'];
        }
        if ($this->users->findByUsername($username)) {
            return ['ok' => false, 'error' => 'This username is already taken.'];
        }

        $displayName = $name !== '' ? $name : $username;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($hash === false) {
            return ['ok' => false, 'error' => 'Could not process password. Please try again.'];
        }

        $user = $this->users->createWithPassword($email, $username, $hash, $displayName);
        if (!$user) {
            return ['ok' => false, 'error' => 'Registration failed. Please try again.'];
        }

        $this->users->updateLastLogin((int)$user['id']);
        $user = $this->users->findById((int)$user['id']);

        return ['ok' => true, 'user' => $user];
    }

    /**
     * @return array{ok:true,user:array}|array{ok:false,error:string}
     */
    public function loginWithPassword(string $identifier, string $password): array
    {
        $identifier = trim($identifier);
        if ($identifier === '' || $password === '') {
            return ['ok' => false, 'error' => 'Invalid email or password.'];
        }

        $user = $this->users->findByEmailOrUsername($identifier);
        if (!$user || empty($user['password_hash'])) {
            return ['ok' => false, 'error' => 'Invalid email or password.'];
        }

        if (!password_verify($password, (string)$user['password_hash'])) {
            return ['ok' => false, 'error' => 'Invalid email or password.'];
        }

        if (password_needs_rehash((string)$user['password_hash'], PASSWORD_DEFAULT)) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            if ($newHash !== false) {
                $this->users->updatePasswordHash((int)$user['id'], $newHash);
                $user = $this->users->findById((int)$user['id']);
            }
        }

        $this->users->updateLastLogin((int)$user['id']);
        $user = $this->users->findById((int)$user['id']);

        return ['ok' => true, 'user' => $user];
    }

    public function generateJWT(array $user): string
    {
        $payload = [
            'iss'   => APP_URL,
            'sub'   => $user['id'],
            'email' => $user['email'],
            'role'  => $user['role'],
            'iat'   => time(),
            'exp'   => time() + (int)($_ENV['JWT_EXPIRY'] ?? 3600),
        ];
        return JWT::encode($payload, $_ENV['JWT_SECRET'] ?? '', 'HS256');
    }

    public function validateJWT(string $token): ?array
    {
        try {
            $secret = $_ENV['JWT_SECRET'] ?? '';
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            return (array)$decoded;
        } catch (\Exception $e) { return null; }
    }

    public function validateState(string $state): bool
    {
        return hash_equals($_SESSION['oauth_state'] ?? '', $state);
    }

    private function generateState(): string
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;
        return $state;
    }

    private function httpPost(string $url, array $data): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        ]);
        $r = curl_exec($ch); curl_close($ch); return $r;
    }
}
