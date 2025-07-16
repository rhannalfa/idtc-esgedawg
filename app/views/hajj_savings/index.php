<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tabungan Haji & Umrah - ITDC Native</title>
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
            background-color: #28a745;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
        }
        .button:hover {
            background-color: #218838;
        }
        .button-secondary {
            background-color: #6c757d;
        }
        .button-secondary:hover {
            background-color: #5a6268;
        }
        p {
            text-align: center;
            margin-top: 20px;
        }
        a.button {
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Daftar Tabungan Haji & Umrah</h1>

        <p><a href="/hajj-savings/create" class="button">Buat Tabungan Baru</a></p>

        <?php if (!empty($savings)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Tabungan</th>
                        <th>Target Dana</th>
                        <th>Dana Terkumpul</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($savings as $saving): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($saving['id']); ?></td>
                            <td>Rp <?php echo number_format($saving['target_amount'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($saving['current_amount'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($saving['status'])); ?></td>
                            <td><?php echo htmlspecialchars($saving['created_at']); ?></td>
                            <td>
                                <?php if ($saving['status'] === 'active'): ?>
                                    <a href="/hajj-savings/<?php echo htmlspecialchars($saving['id']); ?>/deposit" class="button">Setor Dana</a>
                                <?php else: ?>
                                    <span>Sudah Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Anda belum memiliki tabungan haji.</p>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>