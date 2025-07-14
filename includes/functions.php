<?php
// includes/functions.php

// File ini dapat berisi fungsi-fungsi PHP umum yang tidak terkait langsung
// dengan tampilan atau logika aplikasi inti (misalnya, fungsi untuk manipulasi string, array, dll.)

/**
 * Membersihkan input string dari potensi serangan XSS (Cross-Site Scripting).
 * @param string $data String yang akan dibersihkan.
 * @return string String yang sudah dibersihkan.
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Contoh fungsi lain:
// function generate_random_string($length = 10) {
//     $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
//     $charactersLength = strlen($characters);
//     $randomString = '';
//     for ($i = 0; $i < $length; $i++) {
//         $randomString .= $characters[rand(0, $charactersLength - 1)];
//     }
//     return $randomString;
// }