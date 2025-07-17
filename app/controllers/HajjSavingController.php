<?php
// app/controllers/HajjSavingController.php

// Memuat model-model yang dibutuhkan oleh controller ini
require_once __DIR__ . '/../models/HajjSaving.php';
require_once __DIR__ . '/../models/StatusLog.php'; // Digunakan untuk mencatat aktivitas
// require_once __DIR__ . '/../models/MidtransTransaction.php'; // Dikomentari karena belum pakai Midtrans

// Memuat helper functions (seperti view(), redirect())
require_once __DIR__ . '/../../includes/helper.php';

class HajjSavingController
{
    private $hajjSavingModel;
    private $statusLogModel;
    // private $midtransTransactionModel; // Dikomentari

    public function __construct()
    {
        $this->hajjSavingModel = new HajjSaving();
        $this->statusLogModel = new StatusLog();
        // $this->midtransTransactionModel = new MidtransTransaction(); // Dikomentari
    }

    /**
     * Menampilkan daftar tabungan haji/umrah untuk pengguna yang login.
     * Rute: GET /hajj-savings
     */
    public function index()
    {
        // Pastikan pengguna sudah login
        if (!isset($_SESSION['user_id'])) {
            redirect('/login'); // Arahkan ke halaman login jika belum
            return; // Penting: Hentikan eksekusi setelah redirect
        }

        $userId = $_SESSION['user_id'];
        // Ambil semua tabungan haji milik pengguna dari model
        $savings = $this->hajjSavingModel->getByUserId($userId);

        // Muat view daftar tabungan dan kirim data tabungan ke sana
        view('hajj_savings/index', ['savings' => $savings]);
    }

    /**
     * Menampilkan formulir untuk membuat tabungan haji baru.
     * Rute: GET /hajj-savings/create
     */
    public function create()
    {
        // Pastikan pengguna sudah login
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
            return;
        }
        // Muat view formulir pembuatan tabungan
        view('hajj_savings/create');
    }

    /**
     * Memproses pengiriman formulir dan menyimpan tabungan haji baru.
     * Rute: POST /hajj-savings/store
     */
    public function store()
    {
        // Pastikan pengguna sudah login dan request adalah POST
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login'); // Atau tampilkan error 400 Bad Request
            return;
        }

        $userId = $_SESSION['user_id'];
        // Ambil data target_amount dari input form
        $targetAmount = $_POST['target_amount'] ?? 0;

        // Validasi input: target_amount harus lebih dari 0
        if (!is_numeric($targetAmount) || (float)$targetAmount <= 0) {
            // Muat ulang form dengan pesan error jika validasi gagal
            view('hajj_savings/create', ['error' => 'Target dana harus berupa angka positif.']);
            return;
        }

        // Siapkan data untuk disimpan ke database
        $dataToSave = [
            'user_id'        => $userId,
            'target_amount'  => (float)$targetAmount, // Pastikan tipe data float/decimal
            'current_amount' => 0.00,                 // Tabungan awal selalu 0
            'status'         => 'active',             // Status awal tabungan
            // 'created_at' dan 'updated_at' akan diatur otomatis oleh database atau BaseModel
        ];

        // Panggil metode create() di model HajjSaving untuk menyimpan data
        // Metode ini akan mengembalikan ID tabungan yang baru dibuat jika berhasil, atau false jika gagal.
        $hajjSavingId = $this->hajjSavingModel->create($dataToSave);

        if ($hajjSavingId) {
            // Jika penyimpanan berhasil:
            // Catat aktivitas ke log status (opsional tapi bagus untuk audit)
            $this->statusLogModel->create([
                'loggable_type'    => 'HajjSaving',
                'loggable_id'      => $hajjSavingId,
                'new_status'       => 'active',
                'description'      => 'Tabungan haji baru dibuat.',
                'changed_by_user_id' => $userId
            ]);
            // Redirect pengguna ke halaman daftar tabungan dengan pesan sukses
            redirect('/hajj-savings?success=created');
        } else {
            // Jika penyimpanan gagal (misalnya karena error database):
            // Catat error lebih detail ke log server
            error_log("Failed to create hajj saving for user {$userId} with data: " . json_encode($dataToSave));
            // Muat ulang form dengan pesan error generik
            view('hajj_savings/create', ['error' => 'Gagal membuat tabungan haji. Silakan coba lagi.']);
        }
    }

    /**
     * Menampilkan formulir untuk menyetor dana ke tabungan haji tertentu.
     * Rute: GET /hajj-savings/{id}/deposit
     * @param int $id ID tabungan haji yang akan disetor.
     */
    public function depositForm(int $id)
    {
        // Pastikan pengguna sudah login
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
            return;
        }

        $userId = $_SESSION['user_id'];
        // Cari tabungan berdasarkan ID
        $saving = $this->hajjSavingModel->find($id);

        // Validasi: Pastikan tabungan ditemukan dan milik pengguna yang sedang login
        if (!$saving || $saving['user_id'] !== $userId) {
            view('error/404'); // Atau redirect dengan pesan error "Tabungan tidak ditemukan / bukan milik Anda"
            return;
        }
        // Muat view formulir setoran dan kirim data tabungan
        view('hajj_savings/deposit', ['saving' => $saving]);
    }

    /**
     * Memproses pengiriman formulir setoran dana ke tabungan haji.
     * TANPA MIDTRANS: Langsung update current_amount dan status di database.
     * Rute: POST /hajj-savings/deposit/process
     */
    public function processDeposit()
{
    require_once __DIR__ . '/../config/midtrans.php';

    $savingId = $_POST['saving_id'];
    $amount = intval($_POST['amount']);

    // Validasi saving
    $savingModel = new HajjSaving();
    $saving = $savingModel->find($savingId);
    if (!$saving) {
        view('hajj_savings/deposit', ['error' => 'Tabungan tidak ditemukan']);
        return;
    }

    // Buat order_id unik
    $orderId = 'HAJJ-' . time() . '-' . rand(1000, 9999);

    // Simpan sementara order_id, saving_id, amount jika perlu di DB

    // Data Snap
    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $amount,
        ],
        'customer_details' => [
            'first_name' => $_SESSION['user']['name'] ?? 'Nama Default',
            'email' => $_SESSION['user']['email'] ?? 'email@default.com',
        ]
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        view('hajj_savings/payment_page', [
        'snapToken' => $snapToken,
        'savingId' => $savingId,
        'amount' => $amount,
    ]);
    } catch (Exception $e) {
        dd($e->getMessage());
    }
}

public function confirmPayment()
{
    // Cek metode request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        echo "Metode tidak diizinkan";
        exit;
    }

    $savingId = $_POST['saving_id'] ?? null;
    $amount = $_POST['amount'] ?? null;

    // Validasi input
    if (!$savingId || !$amount || !is_numeric($amount)) {
        http_response_code(400); // Bad Request
        echo "Data tidak valid";
        exit;
    }

    // Cek apakah tabungan ada
    $saving = $this->hajjSavingModel->find($savingId);
    if (!$saving) {
        http_response_code(404);
        echo "Tabungan tidak ditemukan";
        exit;
    }

    // Tambah saldo tabungan
    $this->hajjSavingModel->incrementAmount($savingId, (float)$amount);

    // (Opsional) Tambahkan catatan log status
    $this->statusLogModel->create([
        'loggable_type'       => 'HajjSaving',
        'loggable_id'         => $savingId,
        'new_status'          => 'deposit',
        'description'         => 'Setoran berhasil ditambahkan.',
        'changed_by_user_id'  => $_SESSION['user_id'] ?? null
    ]);

    // Redirect
    redirect('/hajj-savings?success=deposit');
}


    // Metode opsional: Untuk memeriksa penyelesaian target (biasanya di cronjob/webhook)
    public function checkTargetCompletion()
    {
        // Logika ini biasanya berjalan di backend (cronjob) atau setelah setiap deposit
        // untuk memeriksa apakah current_amount sudah >= target_amount
    }
}