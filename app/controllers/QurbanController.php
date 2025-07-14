<?php
// app/controllers/QurbanController.php

// Memuat model-model yang dibutuhkan
require_once __DIR__ . '/../models/QurbanAnimal.php';
require_once __DIR__ . '/../models/QurbanTransaction.php';
require_once __DIR__ . '/../models/MidtransTransaction.php';
require_once __DIR__ . '/../models/StatusLog.php';
require_once __DIR__ . '/../models/User.php'; // Untuk mendapatkan data peternak
// Memuat helper untuk fungsi view(), redirect()
require_once __DIR__ . '/../../includes/helper.php';

class QurbanController
{
    private $qurbanAnimalModel;
    private $qurbanTransactionModel;
    private $midtransTransactionModel;
    private $statusLogModel;
    private $userModel;

    public function __construct()
    {
        $this->qurbanAnimalModel = new QurbanAnimal();
        $this->qurbanTransactionModel = new QurbanTransaction();
        $this->midtransTransactionModel = new MidtransTransaction();
        $this->statusLogModel = new StatusLog();
        $this->userModel = new User();
    }

    /**
     * Menampilkan daftar hewan qurban yang tersedia.
     * Fitur: Lihat daftar hewan qurban, Filter berdasarkan lokasi, harga, berat.
     */
    public function index()
    {
        $animals = $this->qurbanAnimalModel->getAvailableAnimals();
        // Implementasi filter bisa ditambahkan di sini berdasarkan $_GET
        // Contoh: $location = $_GET['location'] ?? null;
        //         $minPrice = $_GET['min_price'] ?? null;
        //         $maxPrice = $_GET['max_price'] ?? null;
        //         $animals = $this->qurbanAnimalModel->filterAnimals($location, $minPrice, $maxPrice);

        view('qurban/index', ['animals' => $animals]);
    }

    /**
     * Menampilkan detail hewan qurban.
     * Fitur: Lihat detail hewan (deskripsi, peternak, sertifikat).
     * @param int $id ID hewan qurban.
     */
    public function show($id)
    {
        $animal = $this->qurbanAnimalModel->find($id);
        if ($animal) {
            $peternak = $this->userModel->getUserById($animal['peternak_id']); // Ambil data peternak
            view('qurban/show', ['animal' => $animal, 'peternak' => $peternak]);
        } else {
            view('error/404'); // Tampilan 404 jika hewan tidak ditemukan
        }
    }

    /**
     * Menampilkan form untuk membuat transaksi qurban.
     * Fitur: Pilih metode pembayaran: langsung / cicilan.
     * @param int $animalId ID hewan qurban yang akan dibeli.
     */
    public function createTransaction($animalId)
    {
        // session_start();
        if (!isset($_SESSION['user_id'])) {
            redirect('/login'); // Arahkan ke login jika belum login
        }

        $animal = $this->qurbanAnimalModel->find($animalId);
        if (!$animal) {
            view('error/404');
        }

        view('qurban/create_transaction', ['animal' => $animal]);
    }

    /**
     * Memproses pembuatan transaksi qurban dan inisiasi pembayaran Midtrans.
     * Fitur: Buat transaksi qurban, Bayar via Midtrans.
     */
    public function storeTransaction()
    {
        // session_start();
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        $userId = $_SESSION['user_id'];
        $animalId = $_POST['animal_id'] ?? null;
        $paymentMethod = $_POST['payment_method'] ?? 'full_payment'; // 'full_payment' atau 'installment'
        $amount = $_POST['amount'] ?? 0; // Jumlah yang dibayar (bisa total atau cicilan pertama)

        $animal = $this->qurbanAnimalModel->find($animalId);
        if (!$animal || $amount <= 0) {
            // Handle error: hewan tidak ditemukan atau jumlah tidak valid
            redirect('/qurban');
        }

        // 1. Buat entri Qurban Transaction awal
        $qurbanTransactionData = [
            'user_id' => $userId,
            'animal_id' => $animalId,
            'total_price' => $animal['price'],
            'paid_amount' => 0, // Awalnya 0, akan diupdate setelah pembayaran Midtrans sukses
            'status' => 'pending_payment',
            'created_at' => date('Y-m-d H:i:s')
        ];
        $qurbanTransactionId = $this->qurbanTransactionModel->create($qurbanTransactionData);

        if (!$qurbanTransactionId) {
            // Handle error: Gagal membuat transaksi qurban
            redirect('/qurban');
        }

        // 2. Inisiasi Pembayaran Midtrans
        // Ini adalah bagian yang memerlukan integrasi Midtrans SDK
        // Asumsi Anda sudah menginstal Midtrans SDK via Composer atau mengimplementasikannya secara manual
        require_once __DIR__ . '/../../vendor/autoload.php'; // Jika menggunakan Composer
        $midtransConfig = require __DIR__ . '/../../config/midtrans.php';

        \Midtrans\Config::$serverKey = $midtransConfig['server_key'];
        \Midtrans\Config::$isProduction = $midtransConfig['is_production'];
        \Midtrans\Config::$isSanitized = $midtransConfig['is_sanitized'];
        \Midtrans\Config::$is3ds = $midtransConfig['is_3ds'];

        $orderId = 'QURBAN-' . $qurbanTransactionId . '-' . uniqid(); // Order ID unik untuk Midtrans

        $transaction_details = [
            'order_id' => $orderId,
            'gross_amount' => $amount,
        ];

        $customer_details = [
            'first_name' => $_SESSION['user_name'] ?? 'Guest', // Ambil dari session
            'email' => $_SESSION['user_email'] ?? 'guest@example.com', // Asumsi email ada di session
        ];

        $params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            // 'item_details' => [
            //     [
            //         'id' => $animal['id'],
            //         'price' => $animal['price'],
            //         'quantity' => 1,
            //         'name' => $animal['name']
            //     ]
            // ]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            // Simpan data transaksi Midtrans awal ke database
            $midtransTransactionData = [
                'user_id' => $userId,
                'ibadah_type' => 'qurban',
                'midtrans_order_id' => $orderId,
                'amount' => $amount,
                'status' => 'pending', // Status awal dari Midtrans
                'payment_type' => 'snap', // Akan diupdate setelah callback
                'transaction_time' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->midtransTransactionModel->create($midtransTransactionData);

            // Redirect atau tampilkan halaman pembayaran Midtrans
            view('qurban/payment_page', ['snapToken' => $snapToken, 'qurbanTransactionId' => $qurbanTransactionId]);

        } catch (Exception $e) {
            error_log("Midtrans Snap Token error: " . $e->getMessage());
            // Handle error: Gagal mendapatkan Snap Token
            $this->qurbanTransactionModel->update($qurbanTransactionId, ['status' => 'failed_payment']);
            view('qurban/create_transaction', ['animal' => $animal, 'error' => 'Gagal menginisiasi pembayaran.']);
        }
    }

    /**
     * Menampilkan riwayat transaksi qurban untuk pengguna yang login.
     * Fitur: Lihat riwayat pembayaran dan status cicilan.
     */
    public function history()
    {
        // session_start();
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }
        $userId = $_SESSION['user_id'];
        $transactions = $this->qurbanTransactionModel->getByUserId($userId);
        view('qurban/history', ['transactions' => $transactions]);
    }

    // Metode untuk mencetak sertifikat (akan lebih kompleks, mungkin butuh library PDF)
    public function printCertificate($transactionId)
    {
        // Logika untuk mengambil data transaksi dan membuat PDF sertifikat
        // Fitur: Cetak sertifikat setelah lunas.
        // Ini akan memerlukan library PDF seperti FPDF atau TCPDF
        // Contoh: $transaction = $this->qurbanTransactionModel->find($transactionId);
        // if ($transaction && $transaction['status'] === 'paid') {
        //     // Generate PDF
        // } else {
        //     // Handle error
        // }
    }
}