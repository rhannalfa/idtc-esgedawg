<?php
// app/controllers/HomeController.php

// Memuat model yang mungkin dibutuhkan, misalnya User
require_once __DIR__ . '/../models/User.php';
// Memuat helper untuk fungsi view()
require_once __DIR__ . '/../../includes/helper.php';

class HomeController
{
    /**
     * Menampilkan halaman utama aplikasi.
     */
    public function index()
    {
        // Contoh interaksi dengan Model
        $userModel = new User();
        $users = $userModel->getAllUsers(); // Mengambil semua pengguna dari database

        // Memuat tampilan 'home/index' dan meneruskan data pengguna ke dalamnya
        view('home/index', ['users' => $users]);
    }
}