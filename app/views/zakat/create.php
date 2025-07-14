<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tunaikan Zakat - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Tunaikan Zakat</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="/zakat/donate" method="POST">
            <div class="form-group">
                <label for="type">Jenis Zakat:</label>
                <select id="type" name="type" required>
                    <option value="">Pilih Jenis Zakat</option>
                    <option value="zakat_fitrah">Zakat Fitrah</option>
                    <option value="zakat_mal">Zakat Mal</option>
                    <option value="zakat_penghasilan">Zakat Penghasilan</option>
                    <option value="infaq">Infaq</option>
                    <option value="sedekah">Sedekah</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Jumlah Zakat (Rp):</label>
                <input type="number" id="amount" name="amount" min="1000" required>
            </div>
            <button type="submit" class="button">Lanjutkan Pembayaran</button>
            <a href="/" class="button button-secondary">Batal</a>
        </form>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>