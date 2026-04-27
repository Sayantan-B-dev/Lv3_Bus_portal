<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\User;
use App\Core\Session;
use App\Core\Response;
use App\Services\CloudinaryService;

class UserController
{
    private User $userModel;
    private CloudinaryService $cloudinary;

    public function __construct()
    {
        $this->userModel = new User();
        $this->cloudinary = new CloudinaryService();
    }

    public function view(): void
    {
        if (!Session::isLoggedIn()) Response::redirect(APP_URL . '/auth/login');
        $user = Session::getUser();
        // Refresh user data from DB to get latest
        $user = $this->userModel->findById($user['id']);
        Response::view('profile/view', ['user' => $user, 'pageTitle' => 'My Profile']);
    }

    public function edit(): void
    {
        if (!Session::isLoggedIn()) Response::redirect(APP_URL . '/auth/login');
        $user = Session::getUser();
        $user = $this->userModel->findById($user['id']);
        Response::view('profile/edit', ['user' => $user, 'pageTitle' => 'Edit Profile']);
    }

    public function update(): void
    {
        if (!Session::isLoggedIn()) Response::redirect(APP_URL . '/auth/login');
        $user = Session::getUser();
        $userId = (int)$user['id'];

        $data = $_POST;
        
        // Handle Profile Image Upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $url = $this->cloudinary->upload($_FILES['profile_image']['tmp_name']);
            if ($url) $data['profile_image'] = $url;
        }

        // Handle Cover Image Upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $url = $this->cloudinary->upload($_FILES['cover_image']['tmp_name'], 'covers');
            if ($url) $data['cover_image'] = $url;
        }

        // Convert checkbox to tinyint
        $data['is_student'] = isset($_POST['is_student']) ? 1 : 0;

        if ($this->userModel->updateProfile($userId, $data)) {
            $updatedUser = $this->userModel->findById($userId);
            Session::setUser($updatedUser);
            Session::flash('success', 'Profile updated successfully.');
        } else {
            Session::flash('error', 'Failed to update profile.');
        }

        Response::redirect(APP_URL . '/profile');
    }
}
