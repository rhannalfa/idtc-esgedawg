<header class="main-header">
    <div class="container">
        <nav>
            <a href="/" class="logo">ITDC App</a>
            <ul>
                <li><a href="/">Beranda</a></li>
                <li><a href="/qurban">Qurban</a></li>
                <li><a href="/zakat/donate">Zakat</a></li>
                <li><a href="/hajj-savings">Haji & Umrah</a></li>
                <li><a href="/users">Pengguna</a></li>
                <?php
                // Pastikan session sudah dimulai di public/index.php
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                if (isset($_SESSION['user_id'])):
                ?>
                    <li><span>Halo, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Pengguna'); ?></span></li>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="/admin/dashboard">Admin Panel</a></li>
                    <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'peternak'): ?>
                        <li><a href="/peternak/dashboard">Peternak Panel</a></li>
                    <?php endif; ?>
                    <li><a href="/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>