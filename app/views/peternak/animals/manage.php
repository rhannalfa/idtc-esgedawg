<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Hewan Qurban Saya - Peternak</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Kelola Hewan Qurban Saya</h1>
        <a href="/peternak/animals/create" class="button">Tambah Hewan Baru</a>

        <?php if (!empty($animals)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Berat</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($animals as $animal): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($animal['id']); ?></td>
                            <td><?php echo htmlspecialchars($animal['name']); ?></td>
                            <td>Rp <?php echo number_format($animal['price'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($animal['weight']); ?> kg</td>
                            <td><?php echo htmlspecialchars($animal['location']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($animal['status'])); ?></td>
                            <td>
                                <a href="/qurban/<?php echo htmlspecialchars($animal['id']); ?>" class="button button-secondary button-small">Lihat</a>
                                <!-- Tambahkan tombol edit/delete jika diperlukan untuk hewan -->
                                <!-- <a href="/peternak/animals/<?php echo htmlspecialchars($animal['id']); ?>/edit" class="button button-secondary button-small">Edit</a> -->
                                <!-- <form action="/peternak/animals/<?php echo htmlspecialchars($animal['id']); ?>/delete" method="POST" style="display:inline;">
                                    <button type="submit" class="button-danger button-small" onclick="return confirm('Hapus hewan ini?');">Hapus</button>
                                </form> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Anda belum memiliki hewan qurban yang terdaftar.</p>
        <?php endif; ?>

        <h2>Update Status Transaksi Qurban Terkait:</h2>
        <p>Ini adalah bagian di mana peternak dapat memperbarui status pemotongan/pengiriman untuk transaksi yang melibatkan hewan mereka.</p>
        <!-- Anda perlu mengambil data transaksi qurban yang terkait dengan peternak ini -->
        <!-- Misalnya: $qurbanTransactions = $this->qurbanTransactionModel->getTransactionsByPeternakAnimal($peternakId); -->
        <?php if (!empty($qurbanTransactions)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Hewan</th>
                        <th>User Pembeli</th>
                        <th>Status Pembayaran</th>
                        <th>Status Pengiriman</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($qurbanTransactions as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['animal_name']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['user_name']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($transaction['payment_status'])); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($transaction['delivery_status'])); ?></td>
                            <td>
                                <form action="/peternak/transactions/<?php echo htmlspecialchars($transaction['id']); ?>/update-status" method="POST">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="">Pilih Status</option>
                                        <option value="slaughtered" <?php echo ($transaction['delivery_status'] == 'slaughtered') ? 'selected' : ''; ?>>Sudah Dipotong</option>
                                        <option value="delivered" <?php echo ($transaction['delivery_status'] == 'delivered') ? 'selected' : ''; ?>>Sudah Dikirim</option>
                                    </select>
                                </form>
                                <!-- <a href="/peternak/transactions/<?php echo htmlspecialchars($transaction['id']); ?>/upload-certificate" class="button button-secondary button-small">Upload Sertifikat</a> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada transaksi qurban yang perlu dikelola saat ini.</p>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
    <style>
        .button-small {
            padding: 5px 10px;
            font-size: 0.8em;
        }
    </style>
</body>
</html>