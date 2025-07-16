<?php
// app/core/Database.php

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        // Memuat konfigurasi database dari file
        $config = require __DIR__ . '/../config/database.php'; // Path ini sudah benar

        // Pastikan DSN menggunakan port dari $config
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        if (isset($config['port'])) { // Tambahkan port jika ada di konfigurasi
            $dsn .= ";port={$config['port']}";
        }
        $options = $config['options'];

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}