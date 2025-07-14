<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Hewan Qurban - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Detail Hewan Qurban</h1>
        <?php if (!empty($animal)): ?>
            <div class="animal-detail-card">
                <img src="<?php echo htmlspecialchars($animal['photo_url'] ?: 'https://placehold.co/400x300?text=Hewan+Qurban'); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>">
                <h2><?php echo htmlspecialchars($animal['name']); ?></h2>
                <p><strong>Harga:</strong> Rp <?php echo number_format($animal['price'], 0, ',', '.'); ?></p>
                <p><strong>Berat:</strong> <?php echo htmlspecialchars($animal['weight']); ?> kg</p>
                <p><strong>Lokasi:</strong> <?php echo htmlspecialchars($animal['location']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($animal['status'])); ?></p>
                <?php if (!empty($peternak)): ?>
                    <p><strong>Peternak:</strong> <?php echo htmlspecialchars($peternak['name']); ?> (<?php echo htmlspecialchars($peternak['email']); ?>)</p>
                <?php endif; ?>
                <!-- Tambahkan detail sertifikat jika ada -->

                <a href="/qurban/<?php echo htmlspecialchars($animal['id']); ?>/buy" class="button">Beli Sekarang</a>
                <a href="/qurban" class="button button-secondary">Kembali ke Daftar Hewan</a>
            </div>
        <?php else: ?>
            <p>Hewan qurban tidak ditemukan.</p>
            <a href="/qurban" class="button button-secondary">Kembali ke Daftar Hewan</a>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>

<style>
    .animal-detail-card {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        padding: 30px;
        text-align: center;
        max-width: 600px;
        margin: 20px auto;
    }
    .animal-detail-card img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    .animal-detail-card h2 {
        font-size: 2em;
        margin-bottom: 15px;
        color: #333;
    }
    .animal-detail-card p {
        font-size: 1.1em;
        color: #555;
        margin-bottom: 10px;
    }
    .animal-detail-card strong {
        color: #333;
    }
    .animal-detail-card .button {
        margin-top: 20px;
        margin-right: 10px;
    }
</style>