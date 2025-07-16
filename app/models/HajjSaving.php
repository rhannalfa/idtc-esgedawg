<?php
// app/models/HajjSaving.php

require_once __DIR__ . '/BaseModel.php'; // Pastikan path ini benar

class HajjSaving extends BaseModel {
    protected $table = 'haji_savings'; // Nama tabel di database

    /**
     * Membuat entri tabungan haji baru di database.
     * Metode ini dipanggil dari controller.
     *
     * @param array $data Array asosiatif data yang akan disimpan.
     * @return int|false ID dari record yang baru disisipkan jika berhasil, atau false jika gagal.
     */
    public function create(array $data) { // Metode ini dipanggil dari controller (HajjSavingController)
        $columns = [
            'user_id',
            'target_amount',
            'current_amount',
            'status',
            // 'created_at' dan 'updated_at' biasanya diatur otomatis oleh database (TIMESTAMP DEFAULT CURRENT_TIMESTAMP)
        ];

        $filteredData = [];
        foreach ($columns as $col) {
            $filteredData[$col] = $data[$col] ?? null;
        }

        // PENTING: Panggil metode 'create' dari PARENT (BaseModel)
        // karena metode 'create' di BaseModel yang melakukan operasi INSERT.
        return parent::create($filteredData); // <--- PERBAIKI PANGGILAN INI
    }

    /**
     * Mengambil daftar tabungan haji berdasarkan ID pengguna.
     *
     * @param int $userId ID pengguna yang tabungannya ingin diambil.
     * @return array Array asosiatif dari semua tabungan haji milik pengguna tersebut.
     */
    public function getByUserId(int $userId): array {
        try {
            // Menggunakan properti $this->db dari BaseModel untuk koneksi PDO.
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching hajj savings for user ID {$userId}: " . $e->getMessage());
            return [];
        }
    }

    // Metode find() dan update() sudah diwarisi dari BaseModel.
    // Jika Anda ingin menggunakan metode update dari BaseModel, Anda bisa memanggilnya
    // seperti: $this->update($id, $data); di controller atau di model ini.
    // BaseModel::update() sudah ada dan berfungsi.
}