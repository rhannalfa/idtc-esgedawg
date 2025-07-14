<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Hewan Qurban - Peternak</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Tambah Hewan Qurban Baru</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="/peternak/animals" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nama Hewan (misal: Sapi Limosin):</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="price">Harga (Rp):</label>
                <input type="number" id="price" name="price" min="1000000" required>
            </div>
            <div class="form-group">
                <label for="weight">Berat (kg):</label>
                <input type="number" id="weight" name="weight" step="0.1" min="1" required>
            </div>
            <div class="form-group">
                <label for="location">Lokasi:</label>
                <input type="text" id="location" name="location" required>
            </div>
            <div class="form-group">
                <label for="photo">Foto Hewan (Opsional):</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
            <button type="submit" class="button">Simpan Hewan</button>
            <a href="/peternak/dashboard" class="button button-secondary">Batal</a>
        </form>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>