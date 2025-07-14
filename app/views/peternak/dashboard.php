<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peternak - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Dashboard Peternak</h1>
        <p>Selamat datang, Peternak! Kelola hewan qurban Anda di sini.</p>

        <a href="/peternak/animals/create" class="button">Tambah Hewan Qurban Baru</a>
        <a href="/peternak/animals/manage" class="button button-secondary">Kelola Hewan Saya</a>

        <h2>Hewan Qurban Anda:</h2>
        <?php if (!empty($myAnimals)): ?>
            <div class="animal-grid">
                <?php foreach ($myAnimals as $animal): ?>
                    <div class="animal-card">
                        <img src="<?php echo htmlspecialchars($animal['photo_url'] ?: 'https://placehold.co/300x200?text=Hewan+Qurban'); ?>" alt="<?php echo htmlspecialchars($animal['name']); ?>">
                        <h3><?php echo htmlspecialchars($animal['name']); ?></h3>
                        <p>Harga: Rp <?php echo number_format($animal['price'], 0, ',', '.'); ?></p>
                        <p>Berat: <?php echo htmlspecialchars($animal['weight']); ?> kg</p>
                        <p>Lokasi: <?php echo htmlspecialchars($animal['location']); ?></p>
                        <p>Status: <strong><?php echo htmlspecialchars(ucfirst($animal['status'])); ?></strong></p>
                        <a href="/qurban/<?php echo htmlspecialchars($animal['id']); ?>" class="button button-secondary">Lihat Detail</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Anda belum memiliki hewan qurban yang terdaftar.</p>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>

    <style>
        .animal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .animal-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .animal-card img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .animal-card h3 {
            font-size: 1.4em;
            margin-bottom: 10px;
            color: #333;
        }
        .animal-card p {
            font-size: 0.95em;
            color: #666;
            margin-bottom: 5px;
        }
        .animal-card strong {
            color: #4CAF50;
        }
        .animal-card .button {
            margin-top: 15px;
            width: 80%;
        }
    </style>
</body>
</html>