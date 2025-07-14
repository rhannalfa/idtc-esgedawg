<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Qurban - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Riwayat Transaksi Qurban</h1>

        <?php if (!empty($transactions)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Hewan</th>
                        <th>Total Harga</th>
                        <th>Jumlah Dibayar</th>
                        <th>Status</th>
                        <th>Tanggal Transaksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['animal_name'] ?? 'N/A'); ?></td>
                            <td>Rp <?php echo number_format($transaction['total_price'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($transaction['paid_amount'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $transaction['status']))); ?></td>
                            <td><?php echo htmlspecialchars($transaction['created_at']); ?></td>
                            <td>
                                <?php if ($transaction['status'] === 'paid' || $transaction['status'] === 'completed'): ?>
                                    <!-- <a href="/qurban/certificate/<?php echo htmlspecialchars($transaction['id']); ?>" class="button button-secondary">Cetak Sertifikat</a> -->
                                <?php else: ?>
                                    <span>Menunggu Lunas</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Anda belum memiliki riwayat transaksi qurban.</p>
            <a href="/qurban" class="button">Beli Hewan Qurban Sekarang</a>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>