<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Warteg Lumora</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f4f4f4;
        }
        h1 {
            margin-bottom: 20px;
        }
        .admin-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        .btn, .btn-action {
            padding: 8px 12px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin: 2px;
        }
        .btn:hover, .btn-action:hover {
            background: #0056b3;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #b52b3a;
        }
        .total-stok {
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="admin-container">
    <h1>Admin - Kontrol Stok Warteg Lumora</h1>
    <div class="total-stok">Total Stok Tersisa: <span id="totalStok">0</span> porsi</div>
    <table id="stokTable">
        <thead>
            <tr>
                <th>Nama Menu</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <button class="btn btn-danger" onclick="resetStok()">Reset Semua Stok ke Default</button>
</div>

<script>
    const defaultData = [
        { name: `Rawon`, stok: 20, harga: 20000, image: './assets/images/rawon.jpg' },
        { name: `Soto Betawi`, stok: 20, harga: 25000, image: './assets/images/sotobetawi.jpg' },
        { name: `Krupuk`, stok: 100, harga: 2000, image: './assets/images/kerupuk_putih.jpg' },
        { name: `Telur Asin`, stok: 40, harga: 5000, image: './assets/images/telor_asin.jpg' },
        { name: `Es Teh Manis`, stok: 150, harga: 20000, image: './assets/images/es_teh_manis.jpg' },
        { name: `Nasi Padang`, stok: 30, harga: 12000, image: 'nasi-padang.webp' },
        { name: `Mie Sop`, stok: 45, harga: 17000, image: 'miso.webp' },
        { name: `Mie Goreng`, stok: 35, harga: 18000, image: 'mie_goreng.webp' },
        { name: `Ayam Geprek`, stok: 35, harga: 25000, image: 'kulit-ayam-crispy-geprek.jpg' },
    ];

    let food = JSON.parse(localStorage.getItem('foodData'));
    if (!food) {
        localStorage.setItem('foodData', JSON.stringify(defaultData));
        food = defaultData;
    }

    function loadTable() {
        const tbody = document.querySelector('#stokTable tbody');
        tbody.innerHTML = '';
        let totalStok = 0;

        food.forEach((item, index) => {
            totalStok += item.stok;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td>Rp ${item.harga.toLocaleString()}</td>
                <td>${item.stok}</td>
                <td>
                    <button class="btn-action" onclick="ubahStok(${index}, 1)">+ Tambah</button>
                    <button class="btn-action btn-danger" onclick="ubahStok(${index}, -1)">- Kurangi</button>
                </td>
            `;
            tbody.appendChild(row);
        });

        document.getElementById('totalStok').innerText = totalStok;
    }

    function ubahStok(index, delta) {
        const menu = food[index];
        if (delta < 0 && menu.stok <= 0) {
            alert('Stok tidak bisa kurang dari 0!');
            return;
        }
        menu.stok += delta;
        if (menu.stok < 0) menu.stok = 0;

        localStorage.setItem('foodData', JSON.stringify(food));
        loadTable();
    }

    function resetStok() {
        if (confirm('Yakin ingin mereset semua stok ke default?')) {
            localStorage.setItem('foodData', JSON.stringify(defaultData));
            location.reload();
        }
    }

    loadTable();
</script>
</body>
</html>
