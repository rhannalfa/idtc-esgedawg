<?php
// config/database.php

// Pastikan file config/app.php sudah dimuat untuk fungsi loadEnv
require_once __DIR__ . '/app.php';

// Konfigurasi koneksi database Anda menggunakan variabel dari .env
return [
    'host' => getenv('DB_HOST'),
    'dbname' => getenv('DB_DATABASE'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ],
];