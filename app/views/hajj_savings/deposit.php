<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setor Dana Tabungan Haji - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Setor Dana Tabungan Haji & Umrah</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (!empty($saving)): ?>
            <div class="transaction-summary">
                <h2>Tabungan Anda:</h2>
                <p>Target: Rp <?php echo number_format($saving['target_amount'], 0, ',', '.'); ?></p>
                <p>Terkumpul: Rp <?php echo number_format($saving['current_amount'], 0, ',', '.'); ?></p>
                <p>Sisa: Rp <?php echo number_format($saving['target_amount'] - $saving['current_amount'], 0, ',', '.'); ?></p>
            </div>

            <form action="/hajj-savings/deposit" method="POST">
                <input type="hidden" name="saving_id" value="<?php echo htmlspecialchars($saving['id']); ?>">
                <div class="form-group">
                    <label for="amount">Jumlah Setoran (Rp):</label>
                    <input type="number" id="amount" name="amount" min="10000" required>
                    <small>Minimal setoran Rp 10.000</small>
                </div>
                <button type="submit" class="button">Lanjutkan Pembayaran</button>
                <a href="/hajj-savings" class="button button-secondary">Batal</a>
            </form>
        <?php else: ?>
            <p>Tabungan tidak ditemukan.</p>
            <a href="/hajj-savings" class="button button-secondary">Kembali ke Daftar Tabungan</a>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>