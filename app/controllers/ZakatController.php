<?php
// app/controllers/ZakatController.php

// Memuat model-model yang dibutuhkan
require_once __DIR__ . '/../models/ZakatTransaction.php';
require_once __DIR__ . '/../models/MidtransTransaction.php';
require_once __DIR__ . '/../models/StatusLog.php';
// Memuat helper untuk fungsi view(), redirect()
require_once __DIR__ . '/../../includes/helper.php';

class ZakatController
{
    private $zakatTransactionModel;
    private $midtransTransactionModel;
    private $statusLogModel;

    public function __construct()
    {
        $this->zakatTransactionModel = new ZakatTransaction();
        $this->midtransTransactionModel = new MidtransTransaction();
        $this->statusLogModel = new StatusLog();
    }

    /**
     * Menampilkan form untuk membuat transaksi zakat.
     * Fitur: Pilih jenis zakat: fitrah / mal / penghasilan, Input jumlah zakat.
     */
    public function create()
    {
        // session_start();
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        view('zakat/create');
    }

    /**
     * Memproses pembuatan transaksi zakat dan inisiasi pembayaran Midtrans.
     * Fitur: Buat transaksi zakat, Bayar via Midtrans.
     */
    public function store()
    {
        // session_start();
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        $userId = $_SESSION['user_id'];
        $type = $_POST['type'] ?? ''; // Jenis zakat
        $amount = $_POST['amount'] ?? 0;

        if (empty($type) || $amount <= 0) {
            view('zakat/create', ['error' => 'Jenis zakat dan jumlah harus diisi.']);
            return;
        }

        // 1. Buat entri Zakat Transaction awal
        $zakatTransactionData = [
            'user_id' => $userId,
            'type' => $type,
            'amount' => $amount,
            'status' => 'pending_payment',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $zakatTransactionId = $this->zakatTransactionModel->create($zakatTransactionData);

        if (!$zakatTransactionId) {
            view('zakat/create', ['error' => 'Gagal membuat transaksi zakat.']);
            return;
        }

        // 2. Inisiasi Pembayaran Midtrans
        require_once __DIR__ . '/../../vendor/autoload.php';
        $midtransConfig = require __DIR__ . '/../../config/midtrans.php';

        \Midtrans\Config::$serverKey = $midtransConfig['server_key'];
        \Midtrans\Config::$isProduction = $midtransConfig['is_production'];
        \Midtrans\Config::$isSanitized = $midtransConfig['is_sanitized'];
        \Midtrans\Config::$is3ds = $midtransConfig['is_3ds'];

        $orderId = 'ZAKAT-' . $zakatTransactionId . '-' . uniqid();

        $transaction_details = [
            'order_id' => $orderId,
            'gross_amount' => $amount,
        ];

        $customer_details = [
            'first_name' => $_SESSION['user_name'] ?? 'Guest',
            'email' => $_SESSION['user_email'] ?? 'guest@example.com',
        ];

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            // Simpan data transaksi Midtrans awal ke database
            $midtransTransactionData = [
                'user_id' => $userId,
                'ibadah_type' => 'zakat',
                'midtrans_order_id' => $orderId,
                'amount' => $amount,
                'status' => 'pending',
                'payment_type' => 'snap',
                'transaction_time' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->midtransTransactionModel->create($midtransTransactionData);

            view('zakat/payment_page', ['snapToken' => $snapToken, 'zakatTransactionId' => $zakatTransactionId]);

        } catch (Exception $e) {
            error_log("Midtrans Snap Token error: " . $e->getMessage());
            $this->zakatTransactionModel->update($zakatTransactionId, ['status' => 'failed_payment']);
            view('zakat/create', ['error' => 'Gagal menginisiasi pembayaran.']);
        }
    }

    /**
     * Menampilkan riwayat transaksi zakat untuk pengguna yang login.
     * Fitur: Lihat status (menunggu, disetujui, atau ditolak).
     */
    public function history()
    {
        // session_start();
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        $userId = $_SESSION['user_id'];
        $transactions = $this->zakatTransactionModel->getByUserId($userId);
        view('zakat/history', ['transactions' => $transactions]);
    }

    // Metode untuk mencetak bukti bayar/sertifikat (opsional)
    public function printCertificate($transactionId)
    {
        // Logika untuk mengambil data transaksi dan membuat PDF
    }
}