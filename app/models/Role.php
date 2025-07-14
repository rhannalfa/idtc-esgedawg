<?php
// app/models/Role.php

// Memuat kelas BaseModel sebagai parent
require_once __DIR__ . '/BaseModel.php';

class Role extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'roles'; // Mengatur nama tabel yang terkait dengan model ini
    }

    // Anda bisa menambahkan metode spesifik untuk model Role di sini,
    // misalnya untuk mencari role berdasarkan nama:
    public function findByName($name)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE name = :name");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error searching role by name: " . $e->getMessage());
            return null;
        }
    }
}