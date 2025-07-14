<?php
// app/models/QurbanAnimal.php

// Memuat kelas BaseModel sebagai parent
require_once __DIR__ . '/BaseModel.php';

class QurbanAnimal extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'qurban_animals'; // Mengatur nama tabel yang terkait dengan model ini
    }

    // Metode khusus untuk mendapatkan hewan qurban yang tersedia
    public function getAvailableAnimals()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table} WHERE status = 'available' ORDER BY price ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting available qurban animals: " . $e->getMessage());
            return [];
        }
    }

    // Metode khusus untuk mendapatkan hewan qurban oleh peternak
    public function getAnimalsByPeternakId($peternakId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE peternak_id = :peternak_id ORDER BY created_at DESC");
            $stmt->bindParam(':peternak_id', $peternakId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error getting qurban animals by peternak ID {$peternakId}: " . $e->getMessage());
            return [];
        }
    }
}