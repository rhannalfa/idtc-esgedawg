<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Transaksi Ibadah - Admin Panel</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Semua Transaksi Ibadah</h1>

        <h2>Transaksi Qurban</h2>
        <?php if (!empty($qurbanTransactions)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Hewan ID</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($qurbanTransactions as $t): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($t['id']); ?></td>
                            <td><?php echo htmlspecialchars($t['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($t['animal_id']); ?></td>
                            <td>Rp <?php echo number_format($t['total_price'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($t['status'])); ?></td>
                            <td><?php echo htmlspecialchars($t['created_at']); ?></td>
                            <td>
                                <form action="/admin/transactions/qurban/<?php echo htmlspecialchars($t['id']); ?>/verify" method="POST" style="display:inline;">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="">Ubah Status</option>
                                        <option value="pending" <?php echo ($t['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="paid" <?php echo ($t['status'] == 'paid') ? 'selected' : ''; ?>>Paid</option>
                                        <option value="slaughtered" <?php echo ($t['status'] == 'slaughtered') ? 'selected' : ''; ?>>Slaughtered</option>
                                        <option value="delivered" <?php echo ($t['status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo ($t['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada transaksi Qurban.</p>
        <?php endif; ?>

        <h2>Transaksi Zakat</h2>
        <?php if (!empty($zakatTransactions)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($zakatTransactions as $t): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($t['id']); ?></td>
                            <td><?php echo htmlspecialchars($t['user_id']); ?></td>
                            <td><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $t['type']))); ?></td>
                            <td>Rp <?php echo number_format($t['amount'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($t['status'])); ?></td>
                            <td><?php echo htmlspecialchars($t['created_at']); ?></td>
                            <td>
                                <form action="/admin/transactions/zakat/<?php echo htmlspecialchars($t['id']); ?>/verify" method="POST" style="display:inline;">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="">Ubah Status</option>
                                        <option value="pending" <?php echo ($t['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="paid" <?php echo ($t['status'] == 'paid') ? 'selected' : ''; ?>>Paid</option>
                                        <option value="approved" <?php echo ($t['status'] == 'approved') ? 'selected' : ''; ?>>Approved</option>
                                        <option value="rejected" <?php echo ($t['status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada transaksi Zakat.</p>
        <?php endif; ?>

        <h2>Tabungan Haji & Umrah</h2>
        <?php if (!empty($hajjSavings)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Target</th>
                        <th>Terkumpul</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hajjSavings as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['id']); ?></td>
                            <td><?php echo htmlspecialchars($s['user_id']); ?></td>
                            <td>Rp <?php echo number_format($s['target_amount'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($s['current_amount'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($s['status'])); ?></td>
                            <td><?php echo htmlspecialchars($s['created_at']); ?></td>
                            <td>
                                <form action="/admin/transactions/hajj-saving/<?php echo htmlspecialchars($s['id']); ?>/verify" method="POST" style="display:inline;">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="">Ubah Status</option>
                                        <option value="active" <?php echo ($s['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="completed" <?php echo ($s['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo ($s['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada tabungan Haji & Umrah.</p>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>