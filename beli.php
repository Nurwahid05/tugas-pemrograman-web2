<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Form Pembayaran</title>
    <link rel="stylesheet" href="order.css" />
    <style>
        body { font-family: Arial, sans-serif; background:#f9f9f9; }
        .container { max-width: 500px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        h1 { text-align: center; margin-bottom: 20px; }
        #daftarPesanan p { margin: 8px 0; font-size: 16px; }
        #daftarPesanan h2 { margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; text-align: right; }
        label { display: block; margin: 10px 0 5px; }
        input[type="number"], select {
            width: 100%; padding: 8px; font-size: 16px; border-radius: 4px; border: 1px solid #ccc; box-sizing: border-box;
        }
        button {
            margin-top: 15px; width: 100%; padding: 10px; font-size: 18px; background: #28a745; color: white; border: none; border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background: #218838; }
        #qrisContainer {
            margin-top: 15px;
            display: none;
            text-align: center;
        }
        #qrisImage {
            width: 200px;
            height: auto;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detail Pemesanan</h1>
        <div id="daftarPesanan"></div>

        <form id="formBayar" method="post">
            <input type="hidden" name="dataPesanan" id="dataPesanan" />
            <input type="hidden" name="totalBayar" id="totalBayar" />

            <label for="nomorMeja">Nomor Meja:</label>
           <input type="number" name="nomorMeja" id="nomorMeja" required min="1" max="30" />

            <label for="metode">Metode Pembayaran:</label>
            <select name="metode" id="metode" required>
                <option value="Tunai">Tunai</option>
                <option value="DANA">DANA</option>
                <option value="OVO">OVO</option>
            </select>

            <!-- QRIS display -->
            <div id="qrisContainer">
                <p>Silakan scan QR berikut:</p>
                <img id="qrisImage" src="" alt="QRIS">
            </div>

            <button type="submit">Konfirmasi Pembayaran</button>
        </form>
    </div>

    <script>
        const data = JSON.parse(localStorage.getItem('pembelian'));
        const total = localStorage.getItem('total');
        const list = document.getElementById('daftarPesanan');

        if (data && data.length > 0) {
            data.forEach(item => {
                const subtotal = item.harga * item.jumlah;
                const p = document.createElement('p');
                p.textContent = `${item.name} x ${item.jumlah} = Rp${subtotal.toLocaleString('id-ID')}`;
                list.appendChild(p);
            });

            const totalDiv = document.createElement('h2');
            totalDiv.textContent = `Total Bayar: Rp${parseInt(total).toLocaleString('id-ID')}`;
            list.appendChild(totalDiv);
        } else {
            list.innerHTML = "<p>Tidak ada pesanan.</p>";
        }

        // Isi hidden input sebelum submit
        document.getElementById('formBayar').addEventListener('submit', function(e) {
            document.getElementById('dataPesanan').value = JSON.stringify(data);
            document.getElementById('totalBayar').value = total;
            localStorage.removeItem('pembelian');
            localStorage.removeItem('total');
        });

        // Menampilkan QRIS saat metode DANA / OVO dipilih
        const metodeSelect = document.getElementById('metode');
        const qrisContainer = document.getElementById('qrisContainer');
        const qrisImage = document.getElementById('qrisImage');

        metodeSelect.addEventListener('change', function () {
            const metode = metodeSelect.value;

            if (metode === 'DANA') {
                qrisImage.src = 'frame.png'; 
                qrisContainer.style.display = 'block';
            } else if (metode === 'OVO') {
                qrisImage.src = 'frame.png';
                qrisContainer.style.display = 'block';
            } else {
                qrisContainer.style.display = 'none';
            }
        });
    </script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'koneksi.php'; // Pastikan file koneksi.php sudah benar dan terhubung ke database

    $nomorMeja = intval($_POST['nomorMeja']);
    $metode = $_POST['metode'];
    $totalBayar = intval($_POST['totalBayar']);
    $dataPesanan = json_decode($_POST['dataPesanan'], true);

    if ($dataPesanan && is_array($dataPesanan)) {
        foreach ($dataPesanan as $item) {
            $namaMenu = $item['name'];
            $jumlah = $item['jumlah'];
            $harga = $item['harga'];

            $stmt = $conn->prepare("INSERT INTO pesanan (nama_menu, jumlah, harga, nomor_meja, metode_pembayaran) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("siiis", $namaMenu, $jumlah, $harga, $nomorMeja, $metode);
            $stmt->execute();
            $stmt->close();
        }

        echo "<script>
            alert('Pesanan berhasil disimpan, Silahkan ke kasir untuk pembayaran!');
            localStorage.setItem('strukData', JSON.stringify(" . json_encode($dataPesanan) . "));
            localStorage.setItem('strukTotal', $totalBayar);
            localStorage.setItem('strukMeja', $nomorMeja);
            localStorage.setItem('strukMetode', '$metode');
            window.location.href = 'struk.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Data pesanan tidak valid!'); window.history.back();</script>";
        exit;
    }
}
?>
