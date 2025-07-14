<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Edit Pengguna</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (!empty($user)): ?>
            <form action="/users/<?php echo htmlspecialchars($user['id']); ?>/update" method="POST">
                <div class="form-group">
                    <label for="name">Nama:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Password (kosongkan jika tidak ingin mengubah):</label>
                    <input type="password" id="password" name="password">
                </div>
                <button type="submit" class="button">Perbarui Pengguna</button>
                <a href="/users" class="button button-secondary">Batal</a>
            </form>
        <?php else: ?>
            <p>Pengguna tidak ditemukan.</p>
            <a href="/users" class="button button-secondary">Kembali ke Daftar Pengguna</a>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>