<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran</title>
    <link rel="stylesheet" href="order.css">
</head>
<body>
    <div class="container">
        <h1>Struk Pembayaran</h1>
        <div id="rincianStruk"></div>
        <button onclick="window.location.href='index.php'">Kembali ke Beranda</button>
    </div>

    <script>
        const data = JSON.parse(localStorage.getItem('strukData'));
        const total = localStorage.getItem('strukTotal');
        const metode = localStorage.getItem('strukMetode');
        const meja = localStorage.getItem('strukMeja');
        const strukDiv = document.getElementById('rincianStruk');

        if (data && data.length > 0) {
            data.forEach(item => {
                const div = document.createElement('div');
                div.innerHTML = `<p>${item.name} x ${item.jumlah} = Rp${(item.harga * item.jumlah).toLocaleString('id-ID')}</p>`;
                strukDiv.appendChild(div);
            });

            strukDiv.innerHTML += `
                <hr>
                <p><strong>Total:</strong> Rp${parseInt(total).toLocaleString('id-ID')}</p>
                <p><strong>Meja:</strong> ${meja}</p>
                <p><strong>Pembayaran:</strong> ${metode}</p>
            `;
        } else {
            strukDiv.innerHTML = "<p>Tidak ada data struk.</p>";
        }

        // Hapus data setelah ditampilkan
        localStorage.removeItem('strukData');
        localStorage.removeItem('strukTotal');
        localStorage.removeItem('strukMeja');
        localStorage.removeItem('strukMetode');
    </script>
</body>
</html>
