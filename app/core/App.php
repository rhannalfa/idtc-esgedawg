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

        // Rute Umum
        $this->router->add('GET', '/', ['HomeController', 'index']);

        // Rute untuk Otentikasi
        $this->router->add('GET', '/login', ['AuthController', 'showLogin']);
        $this->router->add('POST', '/login', ['AuthController', 'handleLogin']);
        $this->router->add('GET', '/logout', ['AuthController', 'logout']);
        // Anda mungkin perlu rute untuk registrasi juga

        // Rute untuk Manajemen Pengguna (User Management - bisa diakses admin/user sendiri)
        $this->router->add('GET', '/users', ['UserController', 'index']);
        $this->router->add('GET', '/users/create', ['UserController', 'create']);
        $this->router->add('POST', '/users', ['UserController', 'store']);
        $this->router->add('GET', '/users/{id}', ['UserController', 'show']);
        $this->router->add('GET', '/users/{id}/edit', ['UserController', 'edit']);
        $this->router->add('POST', '/users/{id}/update', ['UserController', 'update']);
        $this->router->add('POST', '/users/{id}/delete', ['UserController', 'destroy']);

        // Rute untuk Modul Qurban (User/Jamaah)
        $this->router->add('GET', '/qurban', ['QurbanController', 'index']); // Daftar hewan qurban
        $this->router->add('GET', '/qurban/{id}', ['QurbanController', 'show']); // Detail hewan qurban
        $this->router->add('GET', '/qurban/{id}/buy', ['QurbanController', 'createTransaction']); // Form transaksi
        $this->router->add('POST', '/qurban/transaction', ['QurbanController', 'storeTransaction']); // Proses transaksi & Midtrans
        $this->router->add('GET', '/qurban/history', ['QurbanController', 'history']); // Riwayat transaksi qurban
        // $this->router->add('GET', '/qurban/certificate/{id}', ['QurbanController', 'printCertificate']);

        // Rute untuk Modul Zakat (User/Jamaah)
        $this->router->add('GET', '/zakat/donate', ['ZakatController', 'create']); // Form zakat
        $this->router->add('POST', '/zakat/donate', ['ZakatController', 'store']); // Proses zakat & Midtrans
        $this->router->add('GET', '/zakat/history', ['ZakatController', 'history']); // Riwayat transaksi zakat
        // $this->router->add('GET', '/zakat/certificate/{id}', ['ZakatController', 'printCertificate']);

        // Rute untuk Modul Haji/Umrah (User/Jamaah)
        $this->router->add('GET', '/hajj-savings', ['HajjSavingController', 'index']); // Daftar tabungan haji
        $this->router->add('GET', '/hajj-savings/create', ['HajjSavingController', 'create']); // Form buat tabungan
        $this->router->add('POST', '/hajj-savings/store', ['HajjSavingController', 'store']); // <--- PASTIKAN BARIS INI ADA!
        $this->router->add('GET', '/hajj-savings/{id}/deposit', ['HajjSavingController', 'depositForm']); // Form setor dana
        $this->router->add('POST', '/hajj-savings/deposit/process', ['HajjSavingController', 'processDeposit']); // Proses setor dana

        $this->router->add('GET', '/admin/dashboard', ['AdminController', 'dashboard']);
        $this->router->add('GET', '/admin/users', ['AdminController', 'manageUsers']);
        $this->router->add('GET', '/admin/transactions/ibadah', ['AdminController', 'viewAllIbadahTransactions']);
        $this->router->add('GET', '/admin/transactions/midtrans', ['AdminController', 'viewAllMidtransTransactions']);
        $this->router->add('POST', '/admin/transactions/{type}/{id}/verify', ['AdminController', 'verifyTransaction']);
        // $this->router->add('GET', '/admin/reports/{type}', ['AdminController', 'generateReport']);

        // Rute untuk Peternak Panel (Perlu otorisasi Peternak)
        $this->router->add('GET', '/peternak/dashboard', ['PeternakController', 'dashboard']);
        $this->router->add('GET', '/peternak/animals/create', ['PeternakController', 'createAnimal']);
        $this->router->add('POST', '/peternak/animals', ['PeternakController', 'storeAnimal']);
        $this->router->add('GET', '/peternak/animals/manage', ['PeternakController', 'manageAnimals']);
        $this->router->add('POST', '/peternak/transactions/{id}/update-status', ['PeternakController', 'updateTransactionStatus']);
        // $this->router->add('POST', '/peternak/transactions/{id}/upload-certificate', ['PeternakController', 'uploadCertificate']);

        // Rute untuk Midtrans Webhook (ini adalah endpoint POST yang akan dihubungi Midtrans)
        $this->router->add('POST', '/midtrans/webhook', ['MidtransWebhookController', 'handle']);

        // --- Akhir pendaftaran rute ---

        // Mendispatch permintaan menggunakan router
        $this->router->dispatch();
    }
}