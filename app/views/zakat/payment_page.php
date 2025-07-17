<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Zakat - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">

    <!-- Midtrans Snap JS -->
    <script 
        type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="<?php echo htmlspecialchars(getenv('MIDTRANS_CLIENT_KEY'), ENT_QUOTES, 'UTF-8'); ?>">
    </script>
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Lanjutkan Pembayaran Zakat</h1>
        <p>Silakan klik tombol di bawah ini untuk menyelesaikan pembayaran zakat Anda secara online.</p>

        <?php if (!empty($snapToken)): ?>
            <button id="pay-button" class="button button-primary">Bayar Sekarang</button>
            <p style="margin-top: 20px;">
                <strong>ID Transaksi Zakat:</strong> 
                <?php echo htmlspecialchars($zakatTransactionId ?? 'Tidak tersedia'); ?>
            </p>
        <?php else: ?>
            <p style="color: red;">Gagal mendapatkan token pembayaran dari Midtrans. Silakan coba lagi nanti.</p>
        <?php endif; ?>

        <a href="/zakat/history" class="button button-secondary" style="margin-top: 20px;">Lihat Riwayat Zakat</a>
    </div>

    <?php if (!empty($snapToken)): ?>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            snap.pay('<?php echo $snapToken; ?>', {
                onSuccess: function (result) {
                    alert("Pembayaran zakat berhasil!");
                    console.log("Success:", result);
                    window.location.href = '/zakat/history';
                },
                onPending: function (result) {
                    alert("Pembayaran zakat Anda sedang menunggu konfirmasi.");
                    console.log("Pending:", result);
                    window.location.href = '/zakat/history';
                },
                onError: function (result) {
                    alert("Pembayaran zakat gagal.");
                    console.log("Error:", result);
                    window.location.href = '/zakat/history';
                },
                onClose: function () {
                    alert("Anda menutup popup sebelum menyelesaikan pembayaran.");
                    window.location.href = '/zakat/history';
                }
            });
        };
    </script>
    <?php endif; ?>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
