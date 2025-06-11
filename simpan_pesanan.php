<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "dbwarteg");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari POST
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['pesanan']) && is_array($data['pesanan'])) {
    foreach ($data['pesanan'] as $item) {
        $nama = $conn->real_escape_string($item['name']);
        $jumlah = (int)$item['jumlah'];
        $harga = (int)$item['harga'];
        $meja = (int)$data['nomorMeja'];
        $metode = $conn->real_escape_string($data['metodePembayaran']);

        $conn->query("INSERT INTO pesanan (nama_menu, jumlah, harga, nomor_meja, metode_pembayaran) 
                      VALUES ('$nama', $jumlah, $harga, $meja, '$metode')");
    }
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Data tidak valid"]);
}

$conn->close();
?>
