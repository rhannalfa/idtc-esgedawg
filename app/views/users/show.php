<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengguna - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Detail Pengguna</h1>
        <?php if (!empty($user)): ?>
            <div class="user-detail">
                <p><strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?></p>
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role_name'] ?? 'N/A'); ?></p>
                <p><strong>Dibuat Pada:</strong> <?php echo htmlspecialchars($user['created_at'] ?? 'N/A'); ?></p>
                <p><strong>Diperbarui Pada:</strong> <?php echo htmlspecialchars($user['updated_at'] ?? 'N/A'); ?></p>
            </div>
            <a href="/users/<?php echo htmlspecialchars($user['id']); ?>/edit" class="button">Edit Pengguna</a>
            <a href="/users" class="button button-secondary">Kembali ke Daftar Pengguna</a>
        <?php else: ?>
            <p>Pengguna tidak ditemukan.</p>
            <a href="/users" class="button button-secondary">Kembali ke Daftar Pengguna</a>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>