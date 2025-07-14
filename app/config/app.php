<?php
// app/config/app.php

// Fungsi untuk memuat variabel lingkungan dari file .env
// Ini adalah implementasi sederhana, Anda bisa menggunakan library seperti phpdotenv untuk yang lebih robust
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Lewati komentar
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Muat variabel lingkungan
// Path ini mengarah ke E:\laragon\www\itdc-native-app\.env
loadEnv(__DIR__ . '/../../.env');

// Konfigurasi umum aplikasi Anda
return [
    'name' => getenv('APP_NAME') ?: 'ITDC Native App',
    'env' => getenv('APP_ENV') ?: 'development',
    'debug' => (getenv('APP_ENV') === 'development'), // Aktifkan debug hanya di lingkungan development

    // Path dasar aplikasi, berguna untuk memuat file
    'base_path' => dirname(__DIR__, 2), // Mengarah ke direktori ITDC-NATIVE-APP/
];