<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Transaksi Midtrans - Admin Panel</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Semua Transaksi Midtrans</h1>

        <?php if (!empty($midtransTransactions)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Jenis Ibadah</th>
                        <th>Order ID Midtrans</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tipe Pembayaran</th>
                        <th>Waktu Transaksi</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($midtransTransactions as $t): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($t['id']); ?></td>
                            <td><?php echo htmlspecialchars($t['user_id']); ?></td>
                            <td><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $t['ibadah_type']))); ?></td>
                            <td><?php echo htmlspecialchars($t['midtrans_order_id']); ?></td>
                            <td>Rp <?php echo number_format($t['amount'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($t['status'])); ?></td>
                            <td><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $t['payment_type']))); ?></td>
                            <td><?php echo htmlspecialchars($t['transaction_time']); ?></td>
                            <td><?php echo htmlspecialchars($t['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada transaksi Midtrans.</p>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>