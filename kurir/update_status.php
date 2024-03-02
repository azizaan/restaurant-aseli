<?php
// Lakukan koneksi ke database jika belum dilakukan sebelumnya
include '../components/connect.php';

// Periksa jika request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah order_id ada dalam request
    if (isset($_POST['order_id'])) {
        // Tangkap nilai order_id
        $order_id = $_POST['order_id'];

        // Lakukan update status menjadi completed
        $update_status_query = $conn->prepare("UPDATE orders SET payment_status = 'completed' WHERE id = ?");
        $update_status_query->execute([$order_id]);

        // Periksa apakah query berhasil dijalankan
        if ($update_status_query) {
            // Kirim respons ke JavaScript bahwa pembayaran berhasil
            echo 'Status order berhasil diperbarui.';
        } else {
            // Kirim respons ke JavaScript bahwa terjadi kesalahan saat memperbarui status order
            echo 'Terjadi kesalahan saat memperbarui status order.';
        }
    } else {
        // Kirim respons ke JavaScript jika order_id tidak ditemukan dalam request
        echo 'Order ID tidak ditemukan dalam request.';
    }
} else {
    // Kirim respons ke JavaScript jika request bukan POST
    echo 'Permintaan tidak valid.';
}
?>
