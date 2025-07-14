<?php
// app/controllers/AuthController.php

// Memuat model User untuk otentikasi
require_once __DIR__ . '/../models/User.php';
// Memuat helper untuk fungsi view()
require_once __DIR__ . '/../../includes/helper.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
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

            // Contoh sederhana: Cari pengguna berdasarkan email
            // Dalam aplikasi nyata, Anda akan memverifikasi password dengan hash
            $user = $this->userModel->getUserByEmail($email); // Anda perlu menambahkan metode getUserByEmail di User.php

            if ($user && password_verify($password, $user['password'])) { // Asumsi password di-hash
                // Login berhasil, set session atau cookie
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: /'); // Redirect ke halaman utama
                exit();
            } else {
                // Login gagal, kembali ke halaman login dengan pesan error
                view('auth/login', ['error' => 'Email atau password salah.']);
            }
        }
    }

    /**
     * Menangani proses logout.
     */
    public function logout()
    {
        session_start();
        session_unset();   // Hapus semua variabel session
        session_destroy(); // Hancurkan session
        header('Location: /login'); // Redirect ke halaman login
        exit();
    }
}