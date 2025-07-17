<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Setoran Haji - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
    <!-- Midtrans Snap JS -->
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="<?php echo getenv('MIDTRANS_CLIENT_KEY'); ?>"></script>
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Lanjutkan Pembayaran Setoran Haji</h1>
        <p>Silakan klik tombol di bawah untuk menyelesaikan pembayaran Anda.</p>

        <?php if (isset($snapToken)): ?>
            <button id="pay-button" class="button button-primary">Bayar Sekarang</button>
            <p style="margin-top: 20px;">Jumlah Setoran: Rp <?php echo number_format($amount ?? 0, 0, ',', '.'); ?></p>
            <p>ID Tabungan: <?php echo htmlspecialchars($savingId ?? 'N/A'); ?></p>

            <!-- DEBUG (opsional, boleh dihapus) -->
            <pre style="background:#eee;padding:10px;margin-top:20px;">
Snap Token: <?php echo htmlspecialchars($snapToken); ?>

Saving ID: <?php echo htmlspecialchars($savingId); ?>

Amount: <?php echo htmlspecialchars($amount); ?>
            </pre>
        <?php else: ?>
            <p style="color: red;">Gagal mendapatkan token pembayaran. Silakan coba lagi.</p>
        <?php endif; ?>

        <a href="/hajj-savings" class="button button-secondary">Kembali ke Tabungan Haji</a>
    </div>

    <?php if (isset($snapToken)): ?>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            snap.pay('<?php echo $snapToken; ?>', {
                onSuccess: function(result){
                    fetch('/hajj-savings/confirm-payment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `saving_id=<?php echo $savingId; ?>&amount=<?php echo $amount; ?>`
                    }).then(() => {
                        alert("Setoran tabungan haji berhasil!");
                        window.location.href = '/hajj-savings';
                    });
                },
                onPending: function(result){
                    fetch('/hajj-savings/confirm-payment', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `saving_id=<?php echo $savingId; ?>&amount=<?php echo $amount; ?>`
                    }).then(() => {
                        alert("Setoran tabungan haji Anda sedang menunggu konfirmasi!");
                        window.location.href = '/hajj-savings';
                    });
                },
                onError: function(result){
                    alert("Setoran tabungan haji gagal!");
                    console.log(result);
                    window.location.href = '/hajj-savings';
                },
                onClose: function(){
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                    window.location.href = '/hajj-savings';
                }
            });
        };
    </script>
    <?php endif; ?>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
