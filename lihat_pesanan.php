<?php
$conn = new mysqli("localhost", "root", "", "dbwarteg");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM pesanan ORDER BY tanggal DESC");

echo "<h1>Daftar Pesanan</h1><table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Menu</th><th>Jumlah</th><th>Harga</th><th>Meja</th><th>Pembayaran</th><th>Waktu</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['nama_menu']}</td>
        <td>{$row['jumlah']}</td>
        <td>Rp" . number_format($row['harga'], 0, ',', '.') . "</td>
        <td>{$row['nomor_meja']}</td>
        <td>{$row['metode_pembayaran']}</td>
        <td>{$row['tanggal']}</td>
    </tr>";
}

echo "</table>";

$conn->close();
?>
