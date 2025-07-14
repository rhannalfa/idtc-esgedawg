<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Qurban - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="container">
        <h1>Transaksi Qurban</h1>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <?php if (!empty($animal)): ?>
            <div class="transaction-summary">
                <h2>Anda akan membeli:</h2>
                <h3><?php echo htmlspecialchars($animal['name']); ?></h3>
                <p>Harga: Rp <?php echo number_format($animal['price'], 0, ',', '.'); ?></p>
                <p>Berat: <?php echo htmlspecialchars($animal['weight']); ?> kg</p>
            </div>

            <form action="/qurban/transaction" method="POST">
                <input type="hidden" name="animal_id" value="<?php echo htmlspecialchars($animal['id']); ?>">

                <div class="form-group">
                    <label for="payment_method">Metode Pembayaran:</label>
                    <select id="payment_method" name="payment_method" required>
                        <option value="full_payment">Bayar Penuh (Rp <?php echo number_format($animal['price'], 0, ',', '.'); ?>)</option>
                        <!-- Opsi cicilan bisa diimplementasikan nanti dengan logika yang lebih kompleks -->
                        <!-- <option value="installment">Cicilan</option> -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="amount">Jumlah Pembayaran (untuk pembayaran penuh):</label>
                    <input type="number" id="amount" name="amount" value="<?php echo htmlspecialchars($animal['price']); ?>" readonly required>
                </div>

                <button type="submit" class="button">Lanjutkan Pembayaran</button>
                <a href="/qurban/<?php echo htmlspecialchars($animal['id']); ?>" class="button button-secondary">Batal</a>
            </form>
        <?php else: ?>
            <p>Hewan qurban tidak ditemukan untuk transaksi ini.</p>
            <a href="/qurban" class="button button-secondary">Kembali ke Daftar Hewan</a>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>

    <script>
        // JavaScript untuk menyesuaikan jumlah pembayaran jika ada opsi cicilan
        // const paymentMethodSelect = document.getElementById('payment_method');
        // const amountInput = document.getElementById('amount');
        // const animalPrice = <?php echo json_encode($animal['price'] ?? 0); ?>;

        // paymentMethodSelect.addEventListener('change', function() {
        //     if (this.value === 'full_payment') {
        //         amountInput.value = animalPrice;
        //         amountInput.readOnly = true;
        //     } else if (this.value === 'installment') {
        //         // Logika untuk cicilan, misalnya input jumlah cicilan pertama
        //         amountInput.value = ''; // Kosongkan atau set nilai default cicilan
        //         amountInput.readOnly = false;
        //         alert('Fitur cicilan akan segera hadir!');
        //     }
        // });
    </script>
</body>
</html>

<style>
    .transaction-summary {
        background-color: #e8f5e9; /* Light green background */
        border: 1px solid #a5d6a7; /* Green border */
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: center;
    }
    .transaction-summary h2 {
        color: #2e7d32; /* Darker green */
        font-size: 1.5em;
        margin-bottom: 10px;
    }
    .transaction-summary h3 {
        color: #43a047; /* Medium green */
        font-size: 1.8em;
        margin-bottom: 10px;
    }
    .transaction-summary p {
        font-size: 1.2em;
        color: #555;
    }
</style>