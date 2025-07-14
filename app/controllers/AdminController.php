<?php
// app/controllers/AdminController.php

// Memuat model-model yang dibutuhkan
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/QurbanTransaction.php';
require_once __DIR__ . '/../models/ZakatTransaction.php';
require_once __DIR__ . '/../models/HajjSaving.php';
require_once __DIR__ . '/../models/MidtransTransaction.php';
require_once __DIR__ . '/../models/StatusLog.php';
require_once __DIR__ . '/../models/Role.php';
// Memuat helper untuk fungsi view(), redirect()
require_once __DIR__ . '/../../includes/helper.php';

class AdminController
{
    private $userModel;
    private $qurbanTransactionModel;
    private $zakatTransactionModel;
    private $hajjSavingModel;
    private $midtransTransactionModel;
    private $statusLogModel;
    private $roleModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->qurbanTransactionModel = new QurbanTransaction();
        $this->zakatTransactionModel = new ZakatTransaction();
        $this->hajjSavingModel = new HajjSaving();
        $this->midtransTransactionModel = new MidtransTransaction();
        $this->statusLogModel = new StatusLog();
        $this->roleModel = new Role();
    }

    /**
     * Middleware sederhana untuk memeriksa apakah pengguna adalah Admin.
     * Ini harus dipanggil di awal setiap metode admin.
     */
    private function requireAdmin()
    {
        // session_start();
        if (!isset($_SESSION['user_id'])) {
            redirect('/login'); // Belum login
        }

        $user = $this->userModel->getUserWithRole($_SESSION['user_id']);
        if (!$user || $user['role_name'] !== 'admin') {
            redirect('/'); // Bukan admin, arahkan ke halaman utama atau 403
            // Atau tampilkan view error/403
        }
    }

    /**
     * Menampilkan dashboard statistik untuk admin.
     * Fitur: Dashboard statistik.
     */
    public function dashboard()
    {
        $this->requireAdmin();

        // Contoh data statistik (perlu query database yang lebih kompleks)
        $totalUsers = count($this->userModel->all());
        $totalQurbanTransactions = count($this->qurbanTransactionModel->all());
        $totalZakatTransactions = count($this->zakatTransactionModel->all());
        $totalMidtransTransactions = count($this->midtransTransactionModel->all());

        view('admin/dashboard', [
            'totalUsers' => $totalUsers,
            'totalQurbanTransactions' => $totalQurbanTransactions,
            'totalZakatTransactions' => $totalZakatTransactions,
            'totalMidtransTransactions' => $totalMidtransTransactions,
        ]);
    }

    /**
     * Mengelola daftar user dan peternak.
     * Fitur: Kelola user dan peternak.
     */
    public function manageUsers()
    {
        $this->requireAdmin();
        $users = $this->userModel->getAllUsers(); // Bisa di-join dengan roles
        view('admin/users/index', ['users' => $users]);
    }

    /**
     * Menampilkan semua transaksi ibadah.
     * Fitur: Lihat semua transaksi ibadah.
     */
    public function viewAllIbadahTransactions()
    {
        $this->requireAdmin();
        $qurbanTransactions = $this->qurbanTransactionModel->all();
        $zakatTransactions = $this->zakatTransactionModel->all();
        $hajjSavings = $this->hajjSavingModel->all();

        view('admin/transactions/ibadah_all', [
            'qurbanTransactions' => $qurbanTransactions,
            'zakatTransactions' => $zakatTransactions,
            'hajjSavings' => $hajjSavings,
        ]);
    }

    /**
     * Menampilkan semua transaksi Midtrans.
     * Fitur: Lihat transaksi Midtrans.
     */
    public function viewAllMidtransTransactions()
    {
        $this->requireAdmin();
        $midtransTransactions = $this->midtransTransactionModel->all();
        view('admin/transactions/midtrans_all', ['midtransTransactions' => $midtransTransactions]);
    }

    /**
     * Memverifikasi dan mengkonfirmasi status transaksi.
     * Fitur: Verifikasi & konfirmasi status.
     */
    public function verifyTransaction($transactionType, $id)
    {
        $this->requireAdmin();
        // Logika verifikasi dan update status
        // Contoh: if ($transactionType === 'qurban') {
        //             $this->qurbanTransactionModel->update($id, ['status' => 'approved']);
        //             $this->statusLogModel->logStatus($_SESSION['user_id'], 'qurban', 'approved', null);
        //         }
        redirect('/admin/transactions');
    }

    /**
     * Mencetak laporan PDF / Excel.
     * Fitur: Cetak laporan PDF / Excel.
     */
    public function generateReport($reportType)
    {
        $this->requireAdmin();
        // Logika untuk menghasilkan laporan (membutuhkan library pihak ketiga)
        // Contoh: if ($reportType === 'pdf_users') {
        //             $users = $this->userModel->all();
        //             // Gunakan FPDF/TCPDF untuk membuat PDF
        //         }
    }
}