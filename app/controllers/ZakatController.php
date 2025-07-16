<?php
// app/controllers/ZakatController.php

// Memuat model-model yang dibutuhkan
require_once __DIR__ . '/../models/ZakatTransaction.php';
// require_once __DIR__ . '/../models/MidtransTransaction.php'; // Dikomentari karena belum pakai Midtrans
require_once __DIR__ . '/../models/StatusLog.php';
require_once __DIR__ . '/../models/ZakatType.php';
// Memuat helper untuk fungsi view(), redirect()
require_once __DIR__ . '/../../includes/helper.php'; // Pastikan file ini ada

class ZakatController
{
    private $zakatTransactionModel;
    // private $midtransTransactionModel; // Dikomentari
    private $statusLogModel;
    private $zakatTypeModel;

    public function __construct()
    {
        $this->zakatTransactionModel = new ZakatTransaction();
        // $this->midtransTransactionModel = new MidtransTransaction(); // Dikomentari
        $this->statusLogModel = new StatusLog();
        $this->zakatTypeModel = new ZakatType();
    }

    /**
     * Menampilkan form untuk membuat transaksi zakat.
     * Fitur: Pilih jenis zakat: fitrah / mal / penghasilan, Input jumlah zakat.
     */
    public function create()
    {
        // session_start(); // Pastikan session_start() sudah dipanggil di awal aplikasi (misal di index.php/router)
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        // Ambil semua jenis zakat dari database untuk dropdown
        $zakatTypes = $this->zakatTypeModel->getAllZakatTypes();

        // Kirim data jenis zakat ke view
        view('zakat/create', ['zakatTypes' => $zakatTypes]);
    }

    /**
     * Memproses pembuatan transaksi zakat.
     * TANPA MIDTRANS: Langsung simpan transaksi dengan status 'pending' (untuk verifikasi manual).
     */
    public function store()
    {
        // session_start(); // Pastikan session_start() sudah dipanggil di awal aplikasi (misal di index.php/router)
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        $userId = $_SESSION['user_id'];
        $zakatTypeId = $_POST['zakat_type_id'] ?? '';
        $amount = $_POST['amount'] ?? 0;

        // Validasi dasar
        if (empty($zakatTypeId) || !is_numeric($zakatTypeId) || $amount <= 0) {
            $zakatTypes = $this->zakatTypeModel->getAllZakatTypes(); // Muat ulang jenis zakat
            view('zakat/create', ['error' => 'Jenis zakat dan jumlah harus diisi dengan benar.', 'zakatTypes' => $zakatTypes]);
            return;
        }

        // Validasi tambahan: Pastikan zakatTypeId benar-benar ada di database
        $selectedZakatType = $this->zakatTypeModel->find($zakatTypeId); // Menggunakan metode find dari BaseModel
        if (!$selectedZakatType) {
            $zakatTypes = $this->zakatTypeModel->getAllZakatTypes(); // Muat ulang jenis zakat
            view('zakat/create', ['error' => 'Jenis zakat yang dipilih tidak valid.', 'zakatTypes' => $zakatTypes]);
            return;
        }

        // 1. Buat entri Zakat Transaction
        $zakatTransactionData = [
            'user_id'       => $userId,
            'zakat_type_id' => (int)$zakatTypeId,
            'amount'        => (float)$amount,
            'payment_method' => 'Manual Transfer', // Asumsi pembayaran manual/transfer
            'status'        => 'pending',         // Status awal 'pending' untuk verifikasi manual
            'description'   => 'Pembayaran zakat via aplikasi - Menunggu verifikasi.'
            // 'midtrans_order_id' => null // Tidak digunakan jika tanpa Midtrans
        ];

        // Panggil metode createTransaction() yang ada di ZakatTransaction model
        $zakatTransactionId = $this->zakatTransactionModel->createTransaction($zakatTransactionData);

        if ($zakatTransactionId) {
            // Transaksi berhasil dibuat di database
            // Log status perubahan
            $this->statusLogModel->create([
                'loggable_type' => 'ZakatTransaction',
                'loggable_id' => $zakatTransactionId,
                'new_status' => 'pending_verification',
                'description' => 'Transaksi zakat dibuat, menunggu verifikasi pembayaran manual.',
                'changed_by_user_id' => $userId
            ]);

            // Redirect ke halaman sukses atau riwayat
            redirect('/zakat/history?success=true');
        } else {
            // Jika gagal menyimpan ke database
            $error = 'Gagal menyimpan transaksi zakat ke database. Silakan coba lagi.';
            error_log("Failed to create zakat transaction for user " . ($userId ?? 'Guest') . " with data: " . json_encode($zakatTransactionData));

            $zakatTypes = $this->zakatTypeModel->getAllZakatTypes(); // Muat ulang jenis zakat
            view('zakat/create', ['error' => $error, 'zakatTypes' => $zakatTypes]);
        }
    }

    /**
     * Menampilkan riwayat transaksi zakat untuk pengguna yang login.
     * Fitur: Lihat status (menunggu, disetujui, atau ditolak).
     */
    public function history()
    {
        // session_start(); // Pastikan session_start() sudah dipanggil di awal aplikasi (misal di index.php/router)
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        $userId = $_SESSION['user_id'];
        // Pastikan getTransactionsByUserId ada di ZakatTransaction model dan join dengan zakat_types untuk nama
        $transactions = $this->zakatTransactionModel->getTransactionsByUserId($userId);
        view('zakat/history', ['transactions' => $transactions]);
    }

    // Metode untuk mencetak bukti bayar/sertifikat (opsional)
    public function printCertificate($transactionId)
    {
        // Logika untuk mengambil data transaksi dan membuat PDF
        // Asumsi user sudah login
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        $transaction = $this->zakatTransactionModel->find($transactionId);

        // Pastikan transaksi milik user yang login atau admin
        if (!$transaction || ($transaction['user_id'] != $_SESSION['user_id'] && ($_SESSION['role_id'] ?? 0) != 2 /* admin role */)) {
            redirect('/zakat/history?error=not_authorized');
        }

        // Load view cetak atau generate PDF
        view('zakat/certificate', ['transaction' => $transaction]);
    }
}
