<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Qurban - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
    <!-- Midtrans Snap JS -->
    <script type="text/javascript"
        src="https://app.midtrans.com/snap/snap.js"
        data-client-key="<?php echo getenv('MIDTRANS_CLIENT_KEY'); ?>"></script>
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Lanjutkan Pembayaran Qurban</h1>
        <p>Silakan klik tombol di bawah untuk menyelesaikan pembayaran Anda.</p>

        <?php if (isset($snapToken)): ?>
            <button id="pay-button" class="button button-primary">Bayar Sekarang</button>
            <p style="margin-top: 20px;">Transaksi ID Qurban: <?php echo htmlspecialchars($qurbanTransactionId ?? 'N/A'); ?></p>
        <?php else: ?>
            <p style="color: red;">Gagal mendapatkan token pembayaran. Silakan coba lagi.</p>
        <?php endif; ?>

        <a href="/qurban/history" class="button button-secondary">Lihat Riwayat Transaksi</a>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>

    <?php if (isset($snapToken)): ?>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // SnapToken ada di variabel PHP $snapToken yang diteruskan ke JavaScript
            snap.pay('<?php echo $snapToken; ?>', {
                onSuccess: function(result){
                    /* You may add your own implementation here */
                    alert("Pembayaran berhasil!");
                    console.log(result);
                    window.location.href = '/qurban/history'; // Redirect ke riwayat transaksi
                },
                onPending: function(result){
                    /* You may add your own implementation here */
                    alert("Pembayaran Anda sedang menunggu konfirmasi!");
                    console.log(result);
                    window.location.href = '/qurban/history';
                },
                onError: function(result){
                    /* You may add your own implementation here */
                    alert("Pembayaran gagal!");
                    console.log(result);
                    window.location.href = '/qurban/history';
                },
                onClose: function(){
                    /* You may add your own implementation here */
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                    window.location.href = '/qurban/history';
                }
            });
        };
    </script>
    <?php endif; ?>
</body>
</html>