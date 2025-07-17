<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tunaikan Zakat - ITDC Native</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Tunaikan Zakatmu Sekarang Juga</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"> <?= htmlspecialchars($error) ?> </p>
    <?php endif; ?>

    <form action="/zakat/donate" method="POST" id="zakat-form">
        <div class="form-group">
            <label for="zakat_type_id">Pilih Jenis Zakat:</label>
            <select name="zakat_type_id" id="zakat_type_id" required>
                <option value="">-- Pilih Jenis Zakat --</option>
                <option value="fitrah">Zakat Fitrah</option>
                <option value="mal">Zakat Mal</option>
            </select>
        </div>

        <!-- Form Zakat Fitrah -->
        <div id="fitrah-form" style="display: none;">
            <div class="form-group">
                <label for="kepala_keluarga">Nama Kepala Keluarga:</label>
                <input type="text" name="nama_kepala_keluarga" id="kepala_keluarga">
            </div>
            <div class="form-group">
                <label for="jumlah_anggota">Jumlah Orang:</label>
                <input type="number" name="jumlah_anggota" id="jumlah_anggota" min="1">
            </div>
            <div class="form-group">
                <label>Pembayaran:</label>
                <select id="metode_fitrah" name="metode">
                    <option value="beras">Beras (2.5 kg/orang)</option>
                    <option value="tunai">Uang Tunai (Rp 40.000/orang)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Total Zakat:</label>
                <input type="text" id="total_fitrah" name="amount" readonly>
            </div>
        </div>

        <!-- Form Zakat Mal -->
        <div id="mal-form" style="display: none;">
            <div class="form-group">
                <label>Jenis Zakat Mal:</label>
                <select id="jenis_mal" name="kategori">
                    <option value="emas">Emas / Perak / Logam Mulia</option>
                    <option value="uang">Uang / Surat Berharga</option>
                    <option value="pertanian">Hasil Pertanian</option>
                    <option value="peternakan">Peternakan</option>
                    <option value="perdagangan">Barang Dagangan</option>
                    <option value="penghasilan">Penghasilan / Profesi</option>
                </select>
            </div>
            <div class="form-group">
                <label>Total Harta (Rp):</label>
                <input type="number" id="total_harta" name="total_harta" placeholder="Contoh: 10000000">
            </div>
            <div class="form-group" id="info_pertanian" style="display: none;">
                <label>Jenis Irigasi:</label>
                <select id="jenis_irigasi" name="persen_zakat">
                    <option value="5">Dengan Biaya (5%)</option>
                    <option value="10">Tanpa Biaya (10%)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Keterangan:</label>
                <textarea name="keterangan" rows="2" placeholder="Keterangan tambahan"></textarea>
            </div>
            <div class="form-group">
                <label>Total Zakat:</label>
                <input type="text" id="total_mal" name="amount" readonly>
            </div>
        </div>

        <button type="submit" class="button">Bayar</button>
        <a href="/" class="button button-secondary">Batal</a>
    </form>
</div>

<script>
    const zakatType = document.getElementById('zakat_type_id');
    const fitrahForm = document.getElementById('fitrah-form');
    const malForm = document.getElementById('mal-form');

    zakatType.addEventListener('change', function () {
        fitrahForm.style.display = this.value === 'fitrah' ? 'block' : 'none';
        malForm.style.display = this.value === 'mal' ? 'block' : 'none';
    });

    // Fitrah Kalkulasi
    document.getElementById('jumlah_anggota').addEventListener('input', calcFitrah);
    document.getElementById('metode_fitrah').addEventListener('change', calcFitrah);

    function calcFitrah() {
        const jumlah = parseInt(document.getElementById('jumlah_anggota').value) || 0;
        const metode = document.getElementById('metode_fitrah').value;
        let total = 0;
        if (metode === 'beras') {
            total = jumlah * 2.5 + ' kg beras';
        } else {
            total = jumlah * 40000;
        }
        document.getElementById('total_fitrah').value = total;
    }

    // Zakat Mal Kalkulasi
    document.getElementById('jenis_mal').addEventListener('change', function () {
        document.getElementById('info_pertanian').style.display = this.value === 'pertanian' ? 'block' : 'none';
        calcMal();
    });

    document.getElementById('total_harta').addEventListener('input', calcMal);
    document.getElementById('jenis_irigasi').addEventListener('change', calcMal);

    function calcMal() {
        const jenis = document.getElementById('jenis_mal').value;
        const harta = parseFloat(document.getElementById('total_harta').value) || 0;
        let zakat = 0;

        if (jenis === 'pertanian') {
            const persen = parseFloat(document.getElementById('jenis_irigasi').value);
            zakat = harta * (persen / 100);
        } else if (jenis === 'peternakan') {
            zakat = 'Sesuai ketentuan: 1 ekor / nishab';
        } else {
            zakat = harta * 0.025;
        }

        document.getElementById('total_mal').value = typeof zakat === 'string' ? zakat : Math.floor(zakat);
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
