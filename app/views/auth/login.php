<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container login-container">
        <h1>Login Aplikasi Ibadah</h1>
        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="/login" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="button">Login</button>
            <p style="text-align: center; margin-top: 15px;">Belum punya akun? <a href="/users/create">Daftar di sini</a></p>
        </form>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>

    <style>
        .login-container {
            max-width: 400px;
            margin-top: 50px;
            padding: 30px;
            border: 1px solid #eee;
            background-color: #fcfcfc;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .login-container h1 {
            font-size: 2em;
            margin-bottom: 25px;
            color: #333;
        }
    </style>
</body>
</html>