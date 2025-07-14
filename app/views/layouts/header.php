<header class="main-header">
    <div class="container">
        <nav>
            <a href="/" class="logo">ITDC App</a>
            <ul>
                <li><a href="/">Beranda</a></li>
                <li><a href="/users">Pengguna</a></li>
                <?php
                // Contoh sederhana untuk menampilkan link login/logout
                // Pastikan session sudah dimulai di public/index.php
                if (isset($_SESSION['user_id'])):
                ?>
                    <li><span>Halo, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span></li>
                    <li><a href="/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>