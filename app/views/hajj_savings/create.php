<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tabungan Haji & Umrah Baru - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
    <style>
        .container {
            width: 80%;
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .form-group input[type="number"],
        .form-group input[type="text"] { /* Jika ada input text lain */
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Agar padding tidak menambah lebar */
            font-size: 16px;
        }
        .button {
            background-color: #28a745; /* Warna hijau */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            text-decoration: none; /* Untuk tombol <a> */
            display: inline-block; /* Untuk tombol <a> */
            text-align: center;
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
        p[style="color: red;"] {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fdd;
            border: 1px solid #fbc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Buat Tabungan Haji & Umrah Baru</h1>
        <?php if (isset($error) && !empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form action="/hajj-savings/store" method="POST"> <div class="form-group">
                <label for="target_amount">Target Dana (Rp):</label>
                <input type="number" id="target_amount" name="target_amount" min="1" required placeholder="Contoh: 25000000">
            </div>
            <button type="submit" class="button">Buat Tabungan</button>
            <a href="/hajj-savings" class="button button-secondary">Batal</a> </form>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>