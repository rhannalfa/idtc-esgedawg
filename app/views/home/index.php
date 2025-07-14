<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Selamat Datang di Aplikasi Ibadah ITDC Native!</h1>
        <p>Aplikasi ini membantu Anda mengelola ibadah Qurban, Zakat, dan Haji/Umrah dengan integrasi Midtrans.</p>

        <h2>Fitur Utama:</h2>
        <ul>
            <li><a href="/qurban">Qurban: Cari dan Beli Hewan Qurban</a></li>
            <li><a href="/zakat/donate">Zakat: Tunaikan Zakat Anda</a></li>
            <li><a href="/hajj-savings">Haji & Umrah: Kelola Tabungan Haji</a></li>
            <li><a href="/users">Manajemen Pengguna</a></li>
        </ul>

        <?php if (!empty($users)): ?>
            <h3>Beberapa Pengguna Terdaftar:</h3>
            <ul>
                <?php foreach ($users as $user): ?>
                    <li><?php echo htmlspecialchars($user['name']); ?> (<?php echo htmlspecialchars($user['email']); ?>)</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Tidak ada pengguna ditemukan.</p>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>