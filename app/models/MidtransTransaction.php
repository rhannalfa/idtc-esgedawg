<?php
// app/models/MidtransTransaction.php

// Memuat kelas BaseModel sebagai parent
require_once __DIR__ . '/BaseModel.php';

class MidtransTransaction extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'midtrans_transactions'; // Mengatur nama tabel yang terkait dengan model ini
    }

    // Metode khusus untuk mencari transaksi berdasarkan Midtrans Order ID
    public function findByMidtransOrderId($orderId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE midtrans_order_id = :order_id");
            $stmt->bindParam(':order_id', $orderId);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error finding Midtrans transaction by order ID {$orderId}: " . $e->getMessage());
            return null;
        }
    }
}