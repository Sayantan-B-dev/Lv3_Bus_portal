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
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;

    public function __construct()
    {
        $this->clientId     = $_ENV['GOOGLE_CLIENT_ID']     ?? '';
        $this->clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? '';
        $this->redirectUri  = $_ENV['GOOGLE_REDIRECT_URI']  ?? '';
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
        return (new User())->findOrCreate($googleUser);
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
