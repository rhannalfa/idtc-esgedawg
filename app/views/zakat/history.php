<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Zakat - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .container {
            width: 90%;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table thead tr th {
            background-color: #f2f2f2;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table tbody tr td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .button {
            background-color: #28a745; /* Warna hijau */
            color: white;
            padding: 8px 12px; /* Lebih kecil untuk di tabel */
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .button:hover {
            background-color: #218838;
        }
        .button-secondary {
            background-color: #6c757d; /* Warna abu-abu */
        }
        .button-secondary:hover {
            background-color: #5a6268;
        }
        p {
            text-align: center;
            margin-top: 20px;
        }
        a.button { /* Style untuk tombol link di bagian bawah */
            margin-top: 20px;
            display: inline-block;
        }
    </style>
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
                            <td><?php echo htmlspecialchars($transaction['zakat_type_name']); ?></td>
                            <td>Rp <?php echo number_format($transaction['amount'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($transaction['status'])); ?></td>
                            <td><?php echo htmlspecialchars($transaction['created_at']); ?></td>
                            <td>
                                <?php if ($transaction['status'] === 'paid' || $transaction['status'] === 'approved'): ?>
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
            <a href="/zakat/create" class="button">Tunaikan Zakat Sekarang</a> <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>