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
            $user = Session::getUser();
            $token = Session::get('admin_token');
            if ($token && $this->auth->validateJWT($token)) {
                if (in_array($user['role'] ?? '', ['admin', 'super_admin'], true)) {
                    Response::redirect(APP_URL . '/admin');
                }
                Response::redirect(APP_URL . '/');
            } else {
                Session::logout(); // Token expired or missing, force fresh login
            }
        }
        Response::view('auth/login', ['pageTitle' => 'Admin Login']);
    }

    public function adminLogin(array $params = []): void
    {
        if (Session::isLoggedIn()) {
            $user = Session::getUser();
            $token = Session::get('admin_token');
            if ($token && $this->auth->validateJWT($token)) {
                if (in_array($user['role'] ?? '', ['admin', 'super_admin'], true)) {
                    Response::redirect(APP_URL . '/admin');
                }
                Response::redirect(APP_URL . '/');
            } else {
                Session::logout();
            }
        }
        Response::view('auth/adminLogin', ['pageTitle' => 'Administrator Access']);
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

        if (in_array($user['role'] ?? '', ['admin', 'super_admin'], true)) {
            Response::redirect(APP_URL . '/admin');
        }

        // Redirect to profile edit if basic info is missing
        if (empty($user['username']) || empty($user['phone'])) {
            Session::flash('info', 'Please complete your profile to continue.');
            Response::redirect(APP_URL . '/profile/edit');
        }

        Response::redirect(APP_URL . '/');
    }

    public function logout(array $params = []): void
    {
        Session::logout();
        Session::flash('success', 'You have been logged out.');
        Response::redirect(APP_URL . '/auth/login');
    }
}
