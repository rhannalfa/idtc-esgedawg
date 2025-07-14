<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Zakat - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Riwayat Transaksi Zakat</h1>

        <?php if (!empty($transactions)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Jenis Zakat</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal Transaksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                            <td><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $transaction['type']))); ?></td>
                            <td>Rp <?php echo number_format($transaction['amount'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($transaction['status'])); ?></td>
                            <td><?php echo htmlspecialchars($transaction['created_at']); ?></td>
                            <td>
                                <?php if ($transaction['status'] === 'paid' || $transaction['status'] === 'approved'): ?>
                                    <!-- <a href="/zakat/certificate/<?php echo htmlspecialchars($transaction['id']); ?>" class="button button-secondary">Cetak Bukti</a> -->
                                <?php else: ?>
                                    <span>Menunggu Konfirmasi</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Anda belum memiliki riwayat transaksi zakat.</p>
            <a href="/zakat/donate" class="button">Tunaikan Zakat Sekarang</a>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>