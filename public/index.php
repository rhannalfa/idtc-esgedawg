<?php
// public/index.php

// Tentukan direktori root proyek untuk kemudahan akses path
// Ini akan mengarah ke direktori 'E:\laragon\www\itdc-native-app'
define('ROOT_PATH', dirname(__DIR__));

// Memuat file konfigurasi aplikasi untuk inisialisasi awal (misal: load .env)
// Path ini mengarah ke E:\laragon\www\itdc-native-app\app\config\app.php
require_once ROOT_PATH . '/app/config/app.php';

// Memuat file helper (misalnya fungsi view(), redirect(), dd())
// Path ini mengarah ke E:\laragon\www\itdc-native-app\includes\helper.php
require_once ROOT_PATH . '/includes/helper.php';

// Memuat kelas App dan memulai aplikasi
require_once ROOT_PATH . '/app/core/App.php';

// Memuat Composer autoloader
require_once ROOT_PATH . '/vendor/autoload.php';

// Inisialisasi session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Buat instance dari kelas App
$app = new App();
// Mulai aplikasi
$app->start();