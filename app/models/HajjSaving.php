<?php
// app/models/HajjSaving.php

// Memuat kelas BaseModel sebagai parent
require_once __DIR__ . '/BaseModel.php';

class HajjSaving extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'hajj_savings'; // Mengatur nama tabel yang terkait dengan model ini
    }

    // Metode khusus untuk mendapatkan tabungan haji berdasarkan user_id
    public function getByUserId($userId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting hajj savings for user_id {$userId}: " . $e->getMessage());
            return [];
        }
    }

    // Metode untuk menambah jumlah tabungan (contoh)
    public function addAmount($id, $amount)
    {
        try {
            $stmt = $this->db->prepare("UPDATE {$this->table} SET current_amount = current_amount + :amount, updated_at = NOW() WHERE id = :id");
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR); // Gunakan PARAM_STR untuk DECIMAL
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Error adding amount to hajj saving ID {$id}: " . $e->getMessage());
            return 0;
        }
    }
}