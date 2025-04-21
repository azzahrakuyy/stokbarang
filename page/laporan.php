<?php
// laporan_transaksi.php
// File ini menyatukan tampilan laporan HTML dan fitur Cetak/Ekspor PDF

// Import Dompdf pada level file (agar tidak menimbulkan parse error)
require_once __DIR__ . '/../lib/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Jika export=pdf dipanggil, langsung-generate PDF dan exit
if (isset($_GET['export']) && $_GET['export'] === 'pdf') {
    // Koneksi database
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

    // Bangun HTML untuk PDF
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

    // Render PDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("laporan_penjualan.pdf", ["Attachment" => false]);
    exit;
}

// —————————————————————————
// TAMPILAN WEB BIASA
// —————————————————————————

// Koneksi database
$conn = new mysqli("localhost", "root", "albira12345", "dbstokbarang");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data transaksi untuk tampilan
$query = "SELECT p.tanggalpenjualan, pl.namapelanggan, p.totalharga
          FROM tb_penjualan p
          JOIN tb_pelanggan pl ON p.pelangganID = pl.pelangganID
          ORDER BY p.tanggalpenjualan DESC, p.penjualanID DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h3 class="mb-4 text-center">Laporan Penjualan</h3>

    <!-- Tombol Cetak / Ekspor PDF -->
    <a href="exportlaporanpdf.php" target="_blank" class="btn btn-outline-primary mb-3">
  <i class="fa fa-file-pdf"></i> Cetak / Ekspor PDF
</a>


    <div class="card shadow">
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Pelanggan</th>
              <th>Total Harga</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $grandTotal = 0;
            while ($tr = $result->fetch_assoc()) {
                $grandTotal += $tr['totalharga'];
            ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= $tr['tanggalpenjualan'] ?></td>
                <td><?= $tr['namapelanggan'] ?></td>
                <td>Rp <?= number_format($tr['totalharga'], 0, ',', '.') ?></td>
              </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr class="table-warning">
              <th colspan="3" class="text-center">Total Keseluruhan</th>
              <th>Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
