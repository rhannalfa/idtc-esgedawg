<?php
// app/controllers/HajjSavingController.php

// Memuat model-model yang dibutuhkan
require_once __DIR__ . '/../models/HajjSaving.php';
require_once __DIR__ . '/../models/MidtransTransaction.php';
require_once __DIR__ . '/../models/StatusLog.php';
// Memuat helper untuk fungsi view(), redirect()
require_once __DIR__ . '/../../includes/helper.php';

class HajjSavingController
{
    private $hajjSavingModel;
    private $midtransTransactionModel;
    private $statusLogModel;

    public function __construct()
    {
        $this->hajjSavingModel = new HajjSaving();
        $this->midtransTransactionModel = new MidtransTransaction();
        $this->statusLogModel = new StatusLog();
    }

    /**
     * Menampilkan daftar tabungan haji/umrah untuk pengguna yang login.
     * Fitur: Lihat progres tabungan.
     */
    public function index()
    {
        // session_start();
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        $userId = $_SESSION['user_id'];
        $savings = $this->hajjSavingModel->getByUserId($userId);
        view('hajj_savings/index', ['savings' => $savings]);
    }

    /**
     * Menampilkan form untuk input target dana dan membuat tabungan baru.
     * Fitur: Input target dana, Buat tabungan.
     */
    public function create()
    {
        // session_start();
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        view('hajj_savings/create');
    }

    /**
     * Menyimpan tabungan haji/umrah baru.
     * Fitur: Sistem hitung cicilan otomatis (logika di frontend atau helper).
     */
    public function store()
    {
        // session_start();
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        $userId = $_SESSION['user_id'];
        $targetAmount = $_POST['target_amount'] ?? 0;

        if ($targetAmount <= 0) {
            view('hajj_savings/create', ['error' => 'Target dana harus lebih dari 0.']);
            return;
        }

        $data = [
            'user_id' => $userId,
            'target_amount' => $targetAmount,
            'current_amount' => 0, // Awalnya 0
            'status' => 'active', // Atau 'pending' jika ada proses verifikasi
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->hajjSavingModel->create($data)) {
            redirect('/hajj-savings');
        } else {
            view('hajj_savings/create', ['error' => 'Gagal membuat tabungan haji.']);
        }
    }

    /**
     * Menampilkan form untuk setor tabungan via Midtrans.
     * Fitur: Setor tabungan via Midtrans.
     * @param int $id ID tabungan haji yang akan disetor.
     */
    public function depositForm($id)
    {
        // session_start();
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        $saving = $this->hajjSavingModel->find($id);
        if (!$saving || $saving['user_id'] !== $_SESSION['user_id']) {
            view('error/404'); // Atau redirect dengan pesan error
        }
        view('hajj_savings/deposit', ['saving' => $saving]);
    }

    /**
     * Memproses setor tabungan via Midtrans.
     * Fitur: Setor tabungan via Midtrans.
     */
    public function processDeposit()
    {
        // session_start();
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        $userId = $_SESSION['user_id'];
        $savingId = $_POST['saving_id'] ?? null;
        $amount = $_POST['amount'] ?? 0;

        $saving = $this->hajjSavingModel->find($savingId);
        if (!$saving || $saving['user_id'] !== $userId || $amount <= 0) {
            view('hajj_savings/deposit', ['error' => 'Data tidak valid.', 'saving' => $saving]);
            return;
        }

        // Inisiasi Pembayaran Midtrans
        require_once __DIR__ . '/../../vendor/autoload.php';
        $midtransConfig = require __DIR__ . '/../../config/midtrans.php';

        \Midtrans\Config::$serverKey = $midtransConfig['server_key'];
        \Midtrans\Config::$isProduction = $midtransConfig['is_production'];
        \Midtrans\Config::$isSanitized = $midtransConfig['is_sanitized'];
        \Midtrans\Config::$is3ds = $midtransConfig['is_3ds'];

        $orderId = 'HAJJDEP-' . $savingId . '-' . uniqid();

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
                'ibadah_type' => 'hajj_saving_deposit',
                'midtrans_order_id' => $orderId,
                'amount' => $amount,
                'status' => 'pending',
                'payment_type' => 'snap',
                'transaction_time' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->midtransTransactionModel->create($midtransTransactionData);

            view('hajj_savings/payment_page', ['snapToken' => $snapToken, 'savingId' => $savingId, 'amount' => $amount]);

        } catch (Exception $e) {
            error_log("Midtrans Snap Token error: " . $e->getMessage());
            view('hajj_savings/deposit', ['error' => 'Gagal menginisiasi pembayaran.', 'saving' => $saving]);
        }
    }

    // Fitur: Notifikasi saat dana cukup (akan diimplementasikan di cronjob atau webhook)
    public function checkTargetCompletion()
    {
        // Logika ini biasanya berjalan di backend (cronjob) atau setelah setiap deposit
        // untuk memeriksa apakah current_amount sudah >= target_amount
    }
}