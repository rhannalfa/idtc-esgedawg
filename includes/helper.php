<?php
// includes/helper.php

/**
 * Memuat file tampilan (view) dan meneruskan data ke dalamnya.
 *
 * @param string $path Nama file view tanpa ekstensi .php (misal: 'home/index', 'auth/login')
 * @param array $data Array asosiatif data yang akan tersedia di view
 */
function view($path, $data = [])
{
    // Mengekstrak array $data menjadi variabel-variabel lokal
    // Contoh: jika $data = ['name' => 'Han'], maka variabel $name akan tersedia di view
    extract($data);

    // Membangun path lengkap ke file view
    $viewPath = __DIR__ . '/../app/views/' . $path . '.php';

    // Memeriksa apakah file view ada
    if (file_exists($viewPath)) {
        require $viewPath; // Memuat file view
    } else {
        // Jika file view tidak ditemukan, hentikan eksekusi dan tampilkan pesan error
        die("View tidak ditemukan: " . htmlspecialchars($viewPath));
    }
}

/**
 * Mengarahkan (redirect) browser ke URL yang diberikan.
 * @param string $url URL tujuan redirect.
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Fungsi untuk debugging sederhana (dump and die).
 * @param mixed $var Variabel yang akan didump.
 */
function dd($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}