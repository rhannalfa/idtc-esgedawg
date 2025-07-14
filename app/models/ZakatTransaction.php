<?php
// app/models/ZakatTransaction.php

// Memuat kelas BaseModel sebagai parent
require_once __DIR__ . '/BaseModel.php';

class ZakatTransaction extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'zakat_transactions'; // Mengatur nama tabel yang terkait dengan model ini
    }

    // Metode khusus untuk mendapatkan transaksi zakat oleh user
    public function getByUserId($userId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting zakat transactions by user ID {$userId}: " . $e->getMessage());
            return [];
        }
    }
}