<?php
// app/controllers/PeternakController.php

// Memuat model-model yang dibutuhkan
require_once __DIR__ . '/../models/QurbanAnimal.php';
require_once __DIR__ . '/../models/QurbanTransaction.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/StatusLog.php';
// Memuat helper untuk fungsi view(), redirect()
require_once __DIR__ . '/../../includes/helper.php';

class PeternakController
{
    private $qurbanAnimalModel;
    private $qurbanTransactionModel;
    private $userModel;
    private $statusLogModel;

    public function __construct()
    {
        $this->qurbanAnimalModel = new QurbanAnimal();
        $this->qurbanTransactionModel = new QurbanTransaction();
        $this->userModel = new User();
        $this->statusLogModel = new StatusLog();
    }

    /**
     * Middleware sederhana untuk memeriksa apakah pengguna adalah Peternak.
     */
    private function requirePeternak()
    {
        // session_start();
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
        }

        $user = $this->userModel->getUserWithRole($_SESSION['user_id']);
        if (!$user || $user['role_name'] !== 'peternak') {
            redirect('/'); // Bukan peternak
            // Atau tampilkan view error/403
        }
    }

    /**
     * Menampilkan dashboard peternak.
     */
    public function dashboard()
    {
        $this->requirePeternak();
        $peternakId = $_SESSION['user_id'];
        $myAnimals = $this->qurbanAnimalModel->getAnimalsByPeternakId($peternakId);
        // Data lain seperti hewan yang sudah dipilih, dll.
        view('peternak/dashboard', ['myAnimals' => $myAnimals]);
    }

    /**
     * Menampilkan form untuk menambah data hewan qurban.
     * Fitur: Tambah data hewan qurban.
     */
    public function createAnimal()
    {
        $this->requirePeternak();
        view('peternak/animals/create');
    }

    /**
     * Menyimpan data hewan qurban baru.
     * Fitur: Upload foto & info hewan.
     */
    public function storeAnimal()
    {
        $this->requirePeternak();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $peternakId = $_SESSION['user_id'];
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $weight = $_POST['weight'] ?? 0;
            $location = $_POST['location'] ?? '';
            $photoUrl = null;

            // Logika upload foto (membutuhkan penanganan file upload)
            // if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            //     $uploadDir = ROOT_PATH . '/public/uploads/animals/';
            //     if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            //     $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
            //     $filePath = $uploadDir . $fileName;
            //     if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
            //         $photoUrl = '/uploads/animals/' . $fileName; // Path yang bisa diakses publik
            //     }
            // }

            $data = [
                'peternak_id' => $peternakId,
                'name' => $name,
                'price' => $price,
                'weight' => $weight,
                'photo_url' => $photoUrl,
                'location' => $location,
                'status' => 'available',
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->qurbanAnimalModel->create($data)) {
                redirect('/peternak/dashboard');
            } else {
                view('peternak/animals/create', ['error' => 'Gagal menambah hewan qurban.']);
            }
        }
    }

    /**
     * Mengelola stok dan status hewan.
     * Fitur: Kelola stok dan status hewan.
     */
    public function manageAnimals()
    {
        $this->requirePeternak();
        $peternakId = $_SESSION['user_id'];
        $animals = $this->qurbanAnimalModel->getAnimalsByPeternakId($peternakId);
        view('peternak/animals/manage', ['animals' => $animals]);
    }

    /**
     * Memperbarui status pemotongan/pengiriman hewan.
     * Fitur: Update status pemotongan/pengiriman.
     */
    public function updateTransactionStatus($transactionId)
    {
        $this->requirePeternak();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newStatus = $_POST['status'] ?? ''; // 'slaughtered', 'delivered'
            $transaction = $this->qurbanTransactionModel->find($transactionId);

            if ($transaction && $transaction['peternak_id'] === $_SESSION['user_id']) { // Pastikan peternak pemilik transaksi
                $this->qurbanTransactionModel->update($transactionId, ['status' => $newStatus, 'updated_at' => date('Y-m-d H:i:s')]);
                $this->statusLogModel->logStatus($_SESSION['user_id'], 'qurban', $newStatus, null);
            }
            redirect('/peternak/dashboard');
        }
    }

    /**
     * Mengunggah sertifikat qurban (opsional).
     * Fitur: Upload sertifikat qurban (opsional).
     */
    public function uploadCertificate($transactionId)
    {
        $this->requirePeternak();
        // Logika upload sertifikat (file upload)
    }
}