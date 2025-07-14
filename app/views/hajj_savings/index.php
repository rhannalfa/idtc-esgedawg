<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabungan Haji & Umrah - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Tabungan Haji & Umrah Anda</h1>
        <a href="/hajj-savings/create" class="button">Buat Tabungan Baru</a>

        <?php if (!empty($savings)): ?>
            <div class="savings-list">
                <?php foreach ($savings as $saving): ?>
                    <div class="saving-card">
                        <h3>Target Dana: Rp <?php echo number_format($saving['target_amount'], 0, ',', '.'); ?></h3>
                        <p>Jumlah Terkumpul: Rp <?php echo number_format($saving['current_amount'], 0, ',', '.'); ?></p>
                        <?php
                            $progress = ($saving['target_amount'] > 0) ? ($saving['current_amount'] / $saving['target_amount']) * 100 : 0;
                            $progress = min(100, max(0, $progress)); // Pastikan antara 0-100
                        ?>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: <?php echo $progress; ?>%;"></div>
                        </div>
                        <p>Progres: <?php echo round($progress, 2); ?>%</p>
                        <p>Status: <?php echo htmlspecialchars(ucfirst($saving['status'])); ?></p>
                        <p>Dibuat: <?php echo htmlspecialchars($saving['created_at']); ?></p>

                        <?php if ($saving['status'] !== 'completed'): ?>
                            <a href="/hajj-savings/<?php echo htmlspecialchars($saving['id']); ?>/deposit" class="button">Setor Dana</a>
                        <?php else: ?>
                            <p style="color: green; font-weight: bold;">Target Tercapai!</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Anda belum memiliki tabungan haji/umrah. <a href="/hajj-savings/create">Buat tabungan pertama Anda</a>.</p>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>

    <style>
        .savings-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .saving-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 20px;
            text-align: center;
        }
        .saving-card h3 {
            color: #2e7d32;
            font-size: 1.4em;
            margin-bottom: 10px;
        }
        .saving-card p {
            color: #555;
            margin-bottom: 8px;
        }
        .progress-bar-container {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 5px;
            height: 20px;
            margin: 15px 0;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            background-color: #4CAF50;
            border-radius: 5px;
            text-align: center;
            color: white;
            line-height: 20px;
            transition: width 0.5s ease-in-out;
        }
        .saving-card .button {
            margin-top: 15px;
        }
    </style>
</body>
</html>