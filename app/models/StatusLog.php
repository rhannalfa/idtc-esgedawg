<?php
// app/models/StatusLog.php

// Memuat kelas BaseModel sebagai parent
require_once __DIR__ . '/BaseModel.php';

class StatusLog extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'status_logs'; // Mengatur nama tabel yang terkait dengan model ini
    }

    // Metode khusus untuk mencatat log status baru
    public function logStatus($userId, $ibadahType, $status, $midtransTransactionId = null)
    {
        $data = [
            'user_id' => $userId,
            'ibadah_type' => $ibadahType,
            'status' => $status,
            'log_time' => date('Y-m-d H:i:s')
        ];

        if ($midtransTransactionId !== null) {
            $data['midtrans_transaction_id'] = $midtransTransactionId;
        }

        return $this->create($data);
    }

    // Metode untuk mendapatkan log status berdasarkan ID transaksi Midtrans
    public function getByMidtransTransactionId($midtransTransactionId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE midtrans_transaction_id = :midtrans_transaction_id ORDER BY log_time ASC");
            $stmt->bindParam(':midtrans_transaction_id', $midtransTransactionId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting status logs for Midtrans transaction ID {$midtransTransactionId}: " . $e->getMessage());
            return [];
        }
    }
}