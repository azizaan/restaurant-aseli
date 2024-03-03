<?php
// Sertakan koneksi ke database atau library yang diperlukan untuk mengambil data
include '../components/connect.php';
// Sertakan autoload.php dari PhpSpreadsheet
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// Ambil bulan dari parameter URL
$bulan = $_GET['bulan'];
// Ambil tahun dari parameter URL (jika ada)
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Array untuk mengonversi nama bulan dalam bahasa Inggris menjadi bahasa Indonesia
$bulan_indonesia = [
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
];

// Tentukan nama bulan berdasarkan nomor bulan
$nama_bulan_inggris = date("F", mktime(0, 0, 0, $bulan, 10));
$nama_bulan_indonesia = $bulan_indonesia[$nama_bulan_inggris];

// Tentukan nama file Excel dengan format 'laporan_namaBulan_tahun.xlsx'
$filename = 'laporan/laporan_' . $nama_bulan_indonesia . '_' . $tahun . '.xlsx';

// Buat objek Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Atur nama kolom
$sheet->setCellValue('A1', 'ID Pesanan');
$sheet->setCellValue('B1', 'Tanggal');
$sheet->setCellValue('C1', 'Nama Pelanggan');
$sheet->setCellValue('D1', 'Total Harga');

// Query untuk mengambil data pesanan berdasarkan bulan dan tahun
$query = "SELECT * FROM orders WHERE MONTH(placed_on) = :bulan AND YEAR(placed_on) = :tahun";
$stmt = $conn->prepare($query);
$stmt->bindParam(':bulan', $bulan);
$stmt->bindParam(':tahun', $tahun);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengisi data pesanan ke dalam Excel
$row_number = 2;
foreach ($rows as $row) {
    $sheet->setCellValue('A' . $row_number, $row['id']);
    $sheet->setCellValue('B' . $row_number, $row['placed_on']);
    $sheet->setCellValue('C' . $row_number, $row['name']);
    // Menambahkan "Rp" di awal dan mengubah format menjadi Rupiah
    $harga_rupiah = $row['total_price']; // Harga dalam angka
    $sheet->setCellValue('D' . $row_number, $harga_rupiah);
    // Mengatur tipe data sel Excel menjadi tipe data angka (Number)
    $sheet->getStyle('D' . $row_number)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $row_number++;
}

// Menyimpan file Excel
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

// Tutup koneksi atau lakukan tindakan pembersihan lainnya
$conn = null;

// Set header untuk mengarahkan ke file Excel yang telah dibuat
header('Location: ' . $filename);
exit;
