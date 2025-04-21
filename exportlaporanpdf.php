<?php
require_once __DIR__ . '/lib/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Koneksi ke database
$conn = new mysqli("localhost", "root", "albira12345", "dbstokbarang");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data transaksi
$sql = "SELECT p.tanggalpenjualan, pl.namapelanggan, p.totalharga
        FROM tb_penjualan p
        JOIN tb_pelanggan pl ON p.pelangganID = pl.pelangganID
        ORDER BY p.tanggalpenjualan DESC, p.penjualanID DESC";
$result = $conn->query($sql);

// Bangun HTML laporan
$html = '<h3 style="text-align:center;">Laporan Penjualan</h3>';
$html .= '<table border="1" cellpadding="6" cellspacing="0" width="100%">';
$html .= '<thead><tr><th>No</th><th>Tanggal</th><th>Pelanggan</th><th>Total Harga</th></tr></thead><tbody>';

$no = 1;
$grandTotal = 0;
while ($row = $result->fetch_assoc()) {
    $html .= '<tr>'
           . '<td>' . $no . '</td>'
           . '<td>' . $row['tanggalpenjualan'] . '</td>'
           . '<td>' . $row['namapelanggan'] . '</td>'
           . '<td>Rp ' . number_format($row['totalharga'], 0, ',', '.') . '</td>'
           . '</tr>';
    $grandTotal += $row['totalharga'];
    $no++;
}
$html .= '</tbody>';
$html .= '<tfoot><tr><th colspan="3" style="text-align:center;">Total Keseluruhan</th>'
       . '<th>Rp ' . number_format($grandTotal, 0, ',', '.') . '</th></tr></tfoot>';
$html .= '</table>';

// Proses PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("laporan_penjualan.pdf", ["Attachment" => false]); // ditampilkan langsung
exit;
