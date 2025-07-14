<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Dashboard Admin</h1>
        <p>Selamat datang, Admin! Berikut adalah ringkasan statistik aplikasi Anda.</p>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Pengguna</h3>
                <p class="stat-number"><?php echo htmlspecialchars($totalUsers ?? 0); ?></p>
                <a href="/admin/users" class="stat-link">Kelola Pengguna</a>
            </div>
            <div class="stat-card">
                <h3>Transaksi Qurban</h3>
                <p class="stat-number"><?php echo htmlspecialchars($totalQurbanTransactions ?? 0); ?></p>
                <a href="/admin/transactions/ibadah" class="stat-link">Lihat Transaksi</a>
            </div>
            <div class="stat-card">
                <h3>Transaksi Zakat</h3>
                <p class="stat-number"><?php echo htmlspecialchars($totalZakatTransactions ?? 0); ?></p>
                <a href="/admin/transactions/ibadah" class="stat-link">Lihat Transaksi</a>
            </div>
            <div class="stat-card">
                <h3>Transaksi Midtrans</h3>
                <p class="stat-number"><?php echo htmlspecialchars($totalMidtransTransactions ?? 0); ?></p>
                <a href="/admin/transactions/midtrans" class="stat-link">Lihat Detail</a>
            </div>
        </div>

        <!-- Tambahkan bagian lain seperti grafik atau laporan cepat -->
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>

    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .stat-card {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 25px;
            text-align: center;
        }
        .stat-card h3 {
            color: #343a40;
            font-size: 1.2em;
            margin-bottom: 15px;
        }
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #007bff; /* Primary color */
            margin-bottom: 15px;
        }
        .stat-link {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .stat-link:hover {
            text-decoration: underline;
        }
    </style>
</body>
</html>