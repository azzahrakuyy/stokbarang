<?php
require 'lib/dompdf/autoload.inc.php'; // Sesuaikan dengan lokasi dompdf

use Dompdf\Dompdf;

$host = "localhost";
$user = "root";
$pass = "albira12345";
$db   = "dbstokbarang";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$query = mysqli_query($conn, "SELECT p.penjualanID, p.tanggalpenjualan, p.totalharga, pl.namapelanggan 
    FROM tb_penjualan p JOIN tb_pelanggan pl ON p.pelangganID = pl.pelangganID ORDER BY p.penjualanID DESC");

$html = '
<h3 style="text-align:center;">Laporan Data Transaksi</h3>
<table border="1" cellpadding="6" cellspacing="0" width="100%">
<thead>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Total Harga</th>
    </tr>
</thead>
<tbody>';

$no = 1;
while ($row = mysqli_fetch_assoc($query)) {
    $html .= "<tr>
        <td>{$no}</td>
        <td>{$row['tanggalpenjualan']}</td>
        <td>{$row['namapelanggan']}</td>
        <td>Rp " . number_format($row['totalharga'], 0, ',', '.') . "</td>
    </tr>";
    $no++;
}

$html .= '</tbody></table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Atur ukuran dan orientasi kertas
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Tampilkan PDF (inline), bisa juga pakai ->stream("file.pdf", array("Attachment" => 1)) untuk auto-download
$dompdf->stream("laporan_transaksi.pdf", array("Attachment" => false));
exit;
?>
