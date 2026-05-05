<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Services\AuthService;
use App\Core\Session;
use App\Core\Response;
use App\Middleware\CsrfMiddleware;

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
        Response::view('auth/login', ['pageTitle' => 'Login']);
    }

    public function loginWithPassword(array $params = []): void
    {
        $expected = CsrfMiddleware::generateToken();
        $token = (string)($_POST['_csrf'] ?? '');
        if (!hash_equals($expected, $token)) {
            Session::flash('error', 'Security check failed. Please try again.');
            Response::redirect(APP_URL . '/auth/login');
        }

        $identifier = trim((string)($_POST['identifier'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $result = $this->auth->loginWithPassword($identifier, $password);
        if (!$result['ok']) {
            Session::flash('error', $result['error']);
            Session::flash('identifier_prefill', $identifier);
            Response::redirect(APP_URL . '/auth/login');
        }

        $this->finalizeLogin($result['user']);
    }

    public function showRegister(array $params = []): void
    {
        if (Session::isLoggedIn()) {
            $user = Session::getUser();
            $token = Session::get('admin_token');
            if ($token && $this->auth->validateJWT($token)) {
                if (in_array($user['role'] ?? '', ['admin', 'super_admin'], true)) {
                    Response::redirect(APP_URL . '/admin');
                }
                Response::redirect(APP_URL . '/');
            }
            Session::logout();
        }
        Response::view('auth/register', ['pageTitle' => 'Create account']);
    }

    public function register(array $params = []): void
    {
        $expected = CsrfMiddleware::generateToken();
        $token = (string)($_POST['_csrf'] ?? '');
        if (!hash_equals($expected, $token)) {
            Session::flash('error', 'Security check failed. Please try again.');
            Response::redirect(APP_URL . '/auth/register');
        }

        $email = (string)($_POST['email'] ?? '');
        $username = (string)($_POST['username'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $passwordConfirm = (string)($_POST['password_confirm'] ?? '');
        $name = trim((string)($_POST['name'] ?? ''));

        if ($password !== $passwordConfirm) {
            Session::flash('error', 'Passwords do not match.');
            Response::redirect(APP_URL . '/auth/register');
        }

        $result = $this->auth->registerLocalUser($email, $username, $password, $name !== '' ? $name : null);
        if (!$result['ok']) {
            Session::flash('error', $result['error']);
            Response::redirect(APP_URL . '/auth/register');
        }

        Session::flash('success', 'Welcome! You are signed in.');
        $this->finalizeLogin($result['user']);
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
        $this->finalizeLogin($user);
    }

    public function logout(array $params = []): void
    {
        Session::logout();
        Session::flash('success', 'You have been logged out.');
        Response::redirect(APP_URL . '/auth/login');
    }

    /** Issue JWT, regenerate session id, set session, redirect by role / profile completeness. */
    private function finalizeLogin(array $user): void
    {
        if (!(int)($user['is_active'] ?? 0)) {
            Session::flash('error', 'Your account has been deactivated. Contact admin.');
            Response::redirect(APP_URL . '/auth/login');
        }

        $jwt = $this->auth->generateJWT($user);
        $user['_jwt'] = $jwt;
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        Session::setUser($user);

        if (in_array($user['role'] ?? '', ['admin', 'super_admin'], true)) {
            Response::redirect(APP_URL . '/admin');
        }

        if (empty($user['username']) || empty($user['phone'])) {
            Session::flash('info', 'Please complete your profile to continue.');
            Response::redirect(APP_URL . '/profile/edit');
        }

        Response::redirect(APP_URL . '/');
    }
}
