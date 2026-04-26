<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Services\AuthService;
use App\Core\Session;
use App\Core\Response;

class AuthController
{
    private AuthService $auth;

    public function __construct()
    {
        $this->auth = new AuthService();
    }

    public function login(array $params = []): void
    {
        if (Session::isLoggedIn()) {
            Response::redirect(APP_URL . '/admin');
        }
        Response::view('auth/login', ['pageTitle' => 'Admin Login']);
    }

    public function googleRedirect(array $params = []): void
    {
        $url = $this->auth->getGoogleAuthUrl();
        Response::redirect($url);
    }

    public function googleCallback(array $params = []): void
    {
        $code  = $_GET['code']  ?? '';
        $state = $_GET['state'] ?? '';

        if (!$this->auth->validateState($state) || !$code) {
            Session::flash('error', 'OAuth state mismatch. Please try again.');
            Response::redirect(APP_URL . '/auth/login');
        }

        $tokens     = $this->auth->exchangeCode($code);
        $googleUser = $this->auth->getGoogleUser($tokens['access_token'] ?? '');

        if (empty($googleUser['sub'])) {
            Session::flash('error', 'Failed to retrieve Google account. Please try again.');
            Response::redirect(APP_URL . '/auth/login');
        }

        $user = $this->auth->findOrCreateUser($googleUser);

        if (!$user['is_active']) {
            Session::flash('error', 'Your account has been deactivated. Contact admin.');
            Response::redirect(APP_URL . '/auth/login');
        }

        $jwt = $this->auth->generateJWT($user);
        $user['_jwt'] = $jwt;
        Session::setUser($user);

        Response::redirect(APP_URL . '/admin');
    }

    public function logout(array $params = []): void
    {
        Session::logout();
        Session::flash('success', 'You have been logged out.');
        Response::redirect(APP_URL . '/auth/login');
    }
}
