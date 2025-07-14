<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css"> <!-- Menggunakan path relatif dari public/ -->
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?> <!-- Memuat bagian header -->

    <div class="container">
        <h1>Selamat Datang di Aplikasi ITDC Native!</h1>
        <p>Ini adalah halaman utama Anda. Anda dapat mulai membangun fitur-fitur di sini.</p>

        <h2>Daftar Pengguna:</h2>
        <?php if (!empty($users)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <a href="/users/<?php echo htmlspecialchars($user['id']); ?>">Lihat</a> |
                                <a href="/users/<?php echo htmlspecialchars($user['id']); ?>/edit">Edit</a> |
                                <form action="/users/<?php echo htmlspecialchars($user['id']); ?>/delete" method="POST" style="display:inline;">
                                    <button type="submit" class="button-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada pengguna ditemukan di database. <a href="/users/create">Tambahkan pengguna baru</a>.</p>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?> <!-- Memuat bagian footer -->
</body>
</html>