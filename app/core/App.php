<?php
// app/core/App.php

// Memuat kelas Router dan Database
require_once __DIR__ . '/Router.php';
require_once __DIR__ . '/Database.php';

class App
{
    protected $router; // Objek Router

    // Konstruktor untuk menginisialisasi Router
    public function __construct()
    {
        $this->router = new Router();
    }

    /**
     * Memulai aplikasi, mendaftarkan rute, dan mendispatch permintaan.
     */
    public function start()
    {
        // --- Daftarkan rute-rute aplikasi Anda di sini ---

        // Rute untuk halaman utama
        $this->router->add('GET', '/', ['HomeController', 'index']);

        // Rute untuk manajemen pengguna (sesuai folder users/ di views)
        $this->router->add('GET', '/users', ['UserController', 'index']);         // Menampilkan daftar pengguna
        $this->router->add('GET', '/users/create', ['UserController', 'create']); // Menampilkan form tambah pengguna
        $this->router->add('POST', '/users', ['UserController', 'store']);        // Menyimpan pengguna baru
        $this->router->add('GET', '/users/{id}', ['UserController', 'show']);     // Menampilkan detail pengguna
        $this->router->add('GET', '/users/{id}/edit', ['UserController', 'edit']);// Menampilkan form edit pengguna
        $this->router->add('POST', '/users/{id}/update', ['UserController', 'update']); // Memperbarui pengguna (menggunakan POST untuk kesederhanaan, bisa juga PUT)
        $this->router->add('POST', '/users/{id}/delete', ['UserController', 'destroy']); // Menghapus pengguna (menggunakan POST untuk kesederhanaan, bisa juga DELETE)

        // Rute untuk otentikasi
        $this->router->add('GET', '/login', ['AuthController', 'showLogin']);
        $this->router->add('POST', '/login', ['AuthController', 'handleLogin']);
        $this->router->add('GET', '/logout', ['AuthController', 'logout']);
        // --- Akhir pendaftaran rute ---

        // Mendispatch permintaan menggunakan router
        $this->router->dispatch();
    }
}