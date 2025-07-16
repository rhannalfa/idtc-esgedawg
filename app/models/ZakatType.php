<?php

// app/models/ZakatType.php

// Pastikan BaseModel.php sudah di-include atau di-autoload
require_once __DIR__ . '/BaseModel.php';

class ZakatType extends BaseModel {
    protected $table = 'zakat_types'; // Nama tabel di database

    public function getAllZakatTypes() {
        try {
            $stmt = $this->db->query("SELECT id, name FROM {$this->table} ORDER BY id ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching zakat types: " . $e->getMessage());
            return []; // Mengembalikan array kosong jika gagal
        }
    }

    public function getById($id) {
        // Menggunakan metode find() dari BaseModel
        return $this->find($id);
    }
}

?>