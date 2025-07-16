<?php

// app/models/ZakatTransaction.php

require_once __DIR__ . '/BaseModel.php'; // Pastikan path ini benar

class ZakatTransaction extends BaseModel {
    protected $table = 'zakat_transactions'; // Nama tabel di database

    /**
     * Metode untuk menyimpan transaksi zakat baru ke database.
     * Menggunakan metode insert() dari BaseModel.
     *
     * @param array $data Data transaksi yang akan disimpan.
     * @return int|false ID transaksi yang baru dibuat jika berhasil, false jika gagal.
     */
    public function createTransaction($data) { // <--- PASTIKAN NAMA METODE INI BENAR
        // Ambil kolom-kolom yang diharapkan oleh tabel zakat_transactions
        $columns = [
            'user_id',
            'zakat_type_id',
            'amount',
            'payment_method',
            'status',
            'description',
            // 'midtrans_order_id' // Komentari jika belum menggunakan Midtrans
        ];

        // Filter data yang akan di-insert agar sesuai dengan kolom yang ada di database
        // dan agar urutannya sesuai dengan yang diharapkan oleh BaseModel::insert()
        $filteredData = [];
        foreach ($columns as $col) {
            // Menggunakan operator null coalescing (??) untuk menangani key yang mungkin tidak ada
            // Memberikan nilai default NULL jika key tidak ada
            $filteredData[$col] = $data[$col] ?? null;
        }

        // Panggil metode insert dari BaseModel
        // BaseModel::insert() mengembalikan true/false untuk status eksekusi.
        // Untuk mendapatkan lastInsertId, kita perlu sedikit penyesuaian.
        try {
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (" . implode(', ', array_keys($filteredData)) . ") VALUES (" . rtrim(str_repeat('?, ', count($filteredData)), ', ') . ")");
            $stmt->execute(array_values($filteredData));
            return $this->db->lastInsertId(); // Mengembalikan ID transaksi yang baru dibuat
        } catch (PDOException $e) {
            error_log("Error creating zakat transaction: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Mengambil riwayat transaksi zakat untuk user tertentu.
     * Menggabungkan dengan tabel zakat_types untuk mendapatkan nama jenis zakat.
     *
     * @param int $userId ID pengguna.
     * @return array Daftar transaksi.
     */
    public function getTransactionsByUserId($userId) {
        // Asumsi $this->db adalah koneksi PDO yang sudah diinisialisasi dari BaseModel
        $stmt = $this->db->prepare("
            SELECT
                zt.*,
                zt2.name AS zakat_type_name
            FROM
                {$this->table} zt
            JOIN
                zakat_types zt2 ON zt.zakat_type_id = zt2.id
            WHERE
                zt.user_id = ?
            ORDER BY
                zt.transaction_date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>