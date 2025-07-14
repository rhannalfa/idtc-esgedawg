<?php
// app/models/QurbanTransaction.php

// Memuat kelas BaseModel sebagai parent
require_once __DIR__ . '/BaseModel.php';

class QurbanTransaction extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'qurban_transactions'; // Mengatur nama tabel yang terkait dengan model ini
    }

    // Metode khusus untuk mendapatkan transaksi qurban oleh user
    public function getByUserId($userId)
    {
        try {
            $stmt = $this->db->prepare("SELECT qt.*, qa.name as animal_name, qa.photo_url FROM {$this->table} qt JOIN qurban_animals qa ON qt.animal_id = qa.id WHERE qt.user_id = :user_id ORDER BY qt.created_at DESC");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting qurban transactions by user ID {$userId}: " . $e->getMessage());
            return [];
        }
    }
}