<?php
// app/models/User.php

// Memuat kelas BaseModel sebagai parent
require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel
{   
    // Konstruktor untuk mengatur nama tabel yang terkait dengan model User
    public function __construct()
    {
        parent::__construct(); // Memanggil konstruktor dari BaseModel
        $this->table = 'users'; // Nama tabel di database yang akan dioperasikan oleh model ini
    }

    /**
     * Mengambil semua data pengguna dari tabel 'users'.
     * Metode ini memanggil metode 'all()' dari BaseModel.
     * @return array Array asosiatif dari semua pengguna.
     */
    public function getAllUsers()
    {
        return $this->all();
    }

    /**
     * Mengambil satu pengguna berdasarkan ID.
     * Metode ini memanggil metode 'find()' dari BaseModel.
     * @param int $id ID pengguna yang dicari.
     * @return array|null Array asosiatif dari pengguna atau null jika tidak ditemukan.
     */
    public function getUserById($id)
    {
        return $this->find($id);
    }

    /**
     * Membuat pengguna baru.
     * Metode ini memanggil metode 'create()' dari BaseModel.
     * @param array $data Array asosiatif data pengguna (misal: ['name' => '...', 'email' => '...', 'password' => '...']).
     * @return int|false ID dari pengguna yang baru dibuat atau false jika gagal.
     */
    public function createUser(array $data)
    {
        // Cukup panggil parent::create()
        return parent::create($data);
    }

    /**
     * Memperbarui data pengguna.
     * Metode ini memanggil metode 'update()' dari BaseModel.
     * @param int $id ID pengguna yang akan diperbarui.
     * @param array $data Array asosiatif data pengguna yang akan diperbarui.
     * @return int Jumlah baris yang terpengaruh.
     */
    public function updateUser($id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Menghapus pengguna.
     * Metode ini memanggil metode 'delete()' dari BaseModel.
     * @param int $id ID pengguna yang akan dihapus.
     * @return int Jumlah baris yang terpengaruh.
     */
    public function deleteUser($id)
    {
        return $this->delete($id);
    }

    /**
     * Mengambil satu pengguna berdasarkan alamat email.
     * Berguna untuk proses login.
     * @param string $email Alamat email pengguna yang dicari.
     * @return array|null Array asosiatif dari pengguna atau null jika tidak ditemukan.
     */
    public function getUserByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error mencari pengguna berdasarkan email: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Mengambil pengguna beserta informasi perannya.
     * Berguna untuk menampilkan data pengguna lengkap dengan role_name.
     * @param int $id ID pengguna yang dicari.
     * @return array|null Array asosiatif dari pengguna dan nama peran, atau null jika tidak ditemukan.
     */
    public function getUserWithRole($id) {
        try {
            $stmt = $this->db->prepare("SELECT u.*, r.name as role_name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error mengambil pengguna dengan peran: " . $e->getMessage());
            return null;
        }
    }
}