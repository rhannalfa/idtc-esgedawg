<?php
// app/controllers/AuthController.php

// Memuat model User untuk otentikasi
require_once __DIR__ . '/../models/User.php';
// Memuat model Role untuk mendapatkan nama peran
require_once __DIR__ . '/../models/Role.php'; // <--- PASTIKAN BARIS INI ADA DI SINI
// Memuat helper untuk fungsi view()
require_once __DIR__ . '/../../includes/helper.php';

class AuthController
{
    private $userModel;
    private $roleModel; // Properti untuk Role model

    public function __construct()
    {
        $this->userModel = new User();
        $this->roleModel = new Role(); // Inisialisasi Role model di konstruktor
    }

    /**
     * Menampilkan halaman form login.
     */
    public function showLogin()
    {
        view('auth/login'); // Asumsi ada view auth/login.php
    }

    /**
     * Menangani proses login.
     */
    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->getUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // session_start(); // Ini sudah dihapus dari sini
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                // Ambil role_name dari database dan simpan di session
                $userRole = $this->roleModel->find($user['role_id']); // Menggunakan $this->roleModel
                $_SESSION['user_role'] = $userRole['name'] ?? 'jamaah';

                header('Location: /');
                exit();
            } else {
                view('auth/login', ['error' => 'Email atau password salah.']);
            }
        }
    }

    /**
     * Menangani proses logout.
     */
    public function logout()
    {
        // session_start(); // Ini sudah dihapus dari sini
        session_unset();
        session_destroy();
        header('Location: /login');
        exit();
    }
}