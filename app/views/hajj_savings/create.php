<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tabungan Haji & Umrah - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Buat Tabungan Haji & Umrah Baru</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="/hajj-savings" method="POST">
            <div class="form-group">
                <label for="target_amount">Target Dana (Rp):</label>
                <input type="number" id="target_amount" name="target_amount" min="1000000" required>
                <small>Contoh: 25000000 untuk Rp 25.000.000</small>
            </div>
            <button type="submit" class="button">Buat Tabungan</button>
            <a href="/hajj-savings" class="button button-secondary">Batal</a>
        </form>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>