<?php
// app/config/midtrans.php

// Pastikan file config/app.php sudah dimuat untuk fungsi loadEnv
// Ini biasanya sudah dilakukan di public/index.php, jadi tidak perlu require_once di sini
// require_once __DIR__ . '/app.php'; // Jika belum dimuat di public/index.php

return [
    'server_key' => getenv('MIDTRANS_SERVER_KEY'),
    'client_key' => getenv('MIDTRANS_CLIENT_KEY'),
    'is_production' => filter_var(getenv('MIDTRANS_IS_PRODUCTION'), FILTER_VALIDATE_BOOLEAN),
    'is_sanitized' => true, // Opsional, default true. Mengaktifkan sanitasi data transaksi.
    'is_3ds' => true,       // Opsional, default true. Mengaktifkan 3D Secure untuk kartu kredit.
];