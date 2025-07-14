<?php
// app/core/Database.php

class Database
{
    private static $instance = null; // Instance tunggal dari kelas Database (Singleton Pattern)
    private $pdo; // Objek PDO untuk koneksi database

    // Konstruktor private untuk mencegah pembuatan instance langsung
    private function __construct()
    {
        // Memuat konfigurasi database dari file
        // Path ini mengarah ke E:\laragon\www\itdc-native-app\app\config\database.php
        $config = require __DIR__ . '/../config/database.php'; // <--- PASTIKAN BARIS INI SEPERTI INI

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $options = $config['options'];

        try {
            // Membuat objek PDO baru
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            // Menangani kegagalan koneksi database
            // Dalam produksi, Anda mungkin ingin mencatat error daripada menampilkan pesan die()
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }

    // Metode statis untuk mendapatkan instance tunggal dari kelas Database
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Metode untuk mendapatkan objek PDO yang terhubung
    public function getConnection()
    {
        return $this->pdo;
    }
}