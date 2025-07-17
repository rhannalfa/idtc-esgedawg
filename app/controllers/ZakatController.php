<?php
// app/controllers/ZakatController.php

require_once __DIR__ . '/../models/ZakatTransaction.php';
require_once __DIR__ . '/../models/StatusLog.php';
require_once __DIR__ . '/../models/ZakatType.php';
require_once __DIR__ . '/../models/ZakatFitrahDetail.php';
require_once __DIR__ . '/../models/ZakatMalDetail.php';
require_once __DIR__ . '/../../includes/helper.php';

class ZakatController
{
    private $zakatTransactionModel;
    private $statusLogModel;
    private $zakatTypeModel;
    private $fitrahDetailModel;
    private $malDetailModel;

    public function __construct()
    {
        $this->zakatTransactionModel = new ZakatTransaction();
        $this->statusLogModel = new StatusLog();
        $this->zakatTypeModel = new ZakatType();
        $this->fitrahDetailModel = new ZakatFitrahDetail();
        $this->malDetailModel = new ZakatMalDetail();
    }

    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $zakatTypes = $this->zakatTypeModel->getAllZakatTypes();
        view('zakat/create', ['zakatTypes' => $zakatTypes]);
    }

    public function store()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/login');
        }

        $userId = $_SESSION['user_id'];
        $zakatTypeId = $_POST['zakat_type_id'] ?? '';
        $amount = 0; // Nanti dihitung otomatis

        if (empty($zakatTypeId) || !is_numeric($zakatTypeId)) {
            $zakatTypes = $this->zakatTypeModel->getAllZakatTypes();
            view('zakat/create', ['error' => 'Jenis zakat harus diisi dengan benar.', 'zakatTypes' => $zakatTypes]);
            return;
        }

        $selectedZakatType = $this->zakatTypeModel->find($zakatTypeId);
        if (!$selectedZakatType) {
            $zakatTypes = $this->zakatTypeModel->getAllZakatTypes();
            view('zakat/create', ['error' => 'Jenis zakat tidak ditemukan.', 'zakatTypes' => $zakatTypes]);
            return;
        }

        $description = '';
        $typeName = strtolower($selectedZakatType['name']);

        if ($typeName === 'zakat fitrah') {
            $namaKK = $_POST['nama_kepala_keluarga'] ?? '';
            $jumlahOrang = (int) ($_POST['jumlah_anggota'] ?? 0);
            $metode = $_POST['metode'] ?? 'tunai';

            if ($jumlahOrang <= 0 || empty($metode)) {
                $zakatTypes = $this->zakatTypeModel->getAllZakatTypes();
                view('zakat/create', ['error' => 'Data zakat fitrah tidak lengkap.', 'zakatTypes' => $zakatTypes]);
                return;
            }

            if ($metode === 'beras') {
                $amount = 0; // Tidak ada nilai uang
                $description = "Zakat fitrah $jumlahOrang orang, dibayar dengan beras 2.5kg per orang.";
            } else {
                $amount = 40000 * $jumlahOrang;
                $description = "Zakat fitrah $jumlahOrang orang, dibayar tunai Rp 40.000 per orang.";
            }

        } elseif ($typeName === 'zakat mal') {
            $kategori = $_POST['kategori'] ?? '';
            $totalHarta = (float) ($_POST['total_harta'] ?? 0);
            $persenZakat = (float) ($_POST['persen_zakat'] ?? 2.5);
            $keterangan = $_POST['keterangan'] ?? '';

            if (empty($kategori) || $totalHarta <= 0) {
                $zakatTypes = $this->zakatTypeModel->getAllZakatTypes();
                view('zakat/create', ['error' => 'Data zakat mal tidak lengkap.', 'zakatTypes' => $zakatTypes]);
                return;
            }

            $amount = $totalHarta * ($persenZakat / 100);
            $description = "Zakat mal kategori $kategori, total harta Rp $totalHarta, zakat $persenZakat%.";

        } else {
            $amount = (float) ($_POST['amount'] ?? 0);
            $description = $_POST['description'] ?? 'Pembayaran zakat.';
        }

        $zakatTransactionData = [
            'user_id' => $userId,
            'zakat_type_id' => (int)$zakatTypeId,
            'amount' => $amount,
            'payment_method' => 'Manual Transfer',
            'status' => 'pending',
            'description' => $description
        ];

        $zakatTransactionId = $this->zakatTransactionModel->createTransaction($zakatTransactionData);

        if ($zakatTransactionId) {
            // Log status awal
            $this->statusLogModel->create([
                'loggable_type' => 'ZakatTransaction',
                'loggable_id' => $zakatTransactionId,
                'new_status' => 'pending_verification',
                'description' => 'Transaksi zakat dibuat, menunggu verifikasi.',
                'changed_by_user_id' => $userId
            ]);

            // Simpan ke tabel detail jika fitrah
            if ($typeName === 'zakat fitrah') {
                $this->fitrahDetailModel->createDetail([
                    'zakat_transaction_id' => $zakatTransactionId,
                    'kepala_keluarga' => $namaKK,
                    'jumlah_anggota' => $jumlahOrang,
                    'metode' => $metode,
                    'total_zakat' => $amount
                ]);
            }

            // Simpan ke tabel detail jika mal
            if ($typeName === 'zakat mal') {
                $this->malDetailModel->createDetail([
                    'zakat_transaction_id' => $zakatTransactionId,
                    'kategori' => $kategori,
                    'total_harta' => $totalHarta,
                    'persen_zakat' => $persenZakat,
                    'keterangan' => $keterangan
                ]);
            }

            redirect('/zakat/history?success=true');
        } else {
            $error = 'Gagal menyimpan transaksi zakat.';
            error_log("Failed to create zakat transaction: " . json_encode($zakatTransactionData));
            $zakatTypes = $this->zakatTypeModel->getAllZakatTypes();
            view('zakat/create', ['error' => $error, 'zakatTypes' => $zakatTypes]);
        }
    }

    public function history()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $userId = $_SESSION['user_id'];
        $transactions = $this->zakatTransactionModel->getTransactionsByUserId($userId);
        view('zakat/history', ['transactions' => $transactions]);
    }

    public function printCertificate($transactionId)
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $transaction = $this->zakatTransactionModel->find($transactionId);

        if (!$transaction || ($transaction['user_id'] != $_SESSION['user_id'] && ($_SESSION['role_id'] ?? 0) != 2)) {
            redirect('/zakat/history?error=not_authorized');
        }

        view('zakat/certificate', ['transaction' => $transaction]);
    }
}
