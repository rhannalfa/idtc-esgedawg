<?php
// app/models/BaseModel.php

// Memuat kelas Database untuk koneksi
require_once __DIR__ . '/../core/Database.php';

class BaseModel
{
    protected $db; // Properti untuk menyimpan objek PDO
    protected $table; // Nama tabel yang terkait dengan model ini

    public function __construct()
    {
        // Mendapatkan koneksi database dari instance Database
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Mengambil semua record dari tabel model.
     * @return array Array asosiatif dari semua record.
     */
    public function all()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM {$this->table}");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error mengambil semua data dari {$this->table}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mengambil satu record berdasarkan ID.
     * @param int $id ID record yang dicari.
     * @return array|null Array asosiatif dari record atau null jika tidak ditemukan.
     */
    public function find($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error mencari data di {$this->table} dengan ID {$id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Menyisipkan data baru ke dalam tabel.
     * @param array $data Array asosiatif data yang akan disisipkan.
     * @return int|false ID dari record yang baru disisipkan atau false jika gagal.
     */
    public function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        try {
            $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
            foreach ($data as $key => &$value) {
                $stmt->bindParam(":$key", $value);
            }
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error membuat data baru di {$this->table}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Memperbarui record yang ada di tabel.
     * @param int $id ID record yang akan diperbarui.
     * @param array $data Array asosiatif data yang akan diperbarui.
     * @return int Jumlah baris yang terpengaruh.
     */
    public function update($id, array $data)
    {
        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setParts);

        try {
            $stmt = $this->db->prepare("UPDATE {$this->table} SET {$setClause} WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            foreach ($data as $key => &$value) {
                $stmt->bindParam(":$key", $value);
            }
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Error memperbarui data di {$this->table} dengan ID {$id}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Menghapus record dari tabel.
     * @param int $id ID record yang akan dihapus.
     * @return int Jumlah baris yang terpengaruh.
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Error menghapus data dari {$this->table} dengan ID {$id}: " . $e->getMessage());
            return 0;
        }
    }
}