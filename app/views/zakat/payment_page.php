<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Zakat - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
    <!-- Midtrans Snap JS -->
    <script type="text/javascript"
        src="https://app.midtrans.com/snap/snap.js"
        data-client-key="<?php echo getenv('MIDTRANS_CLIENT_KEY'); ?>"></script>
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Lanjutkan Pembayaran Zakat</h1>
        <p>Silakan klik tombol di bawah untuk menyelesaikan pembayaran Anda.</p>

        <?php if (isset($snapToken)): ?>
            <button id="pay-button" class="button button-primary">Bayar Sekarang</button>
            <p style="margin-top: 20px;">Transaksi ID Zakat: <?php echo htmlspecialchars($zakatTransactionId ?? 'N/A'); ?></p>
        <?php else: ?>
            <p style="color: red;">Gagal mendapatkan token pembayaran. Silakan coba lagi.</p>
        <?php endif; ?>

        <a href="/zakat/history" class="button button-secondary">Lihat Riwayat Zakat</a>
    </div>

    <?php if (isset($snapToken)): ?>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('<?php echo $snapToken; ?>', {
                onSuccess: function(result){
                    alert("Pembayaran zakat berhasil!");
                    console.log(result);
                    window.location.href = '/zakat/history';
                },
                onPending: function(result){
                    alert("Pembayaran zakat Anda sedang menunggu konfirmasi!");
                    console.log(result);
                    window.location.href = '/zakat/history';
                },
                onError: function(result){
                    alert("Pembayaran zakat gagal!");
                    console.log(result);
                    window.location.href = '/zakat/history';
                },
                onClose: function(){
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                    window.location.href = '/zakat/history';
                }
            });
        };
    </script>
    <?php endif; ?>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>