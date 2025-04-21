<?php
$host = "localhost";
$user = "root";
$pass = "albira12345";
$db   = "dbstokbarang";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$editID = isset($_GET['edit']) ? $_GET['edit'] : null;
$barangLama = [];
$editData = null;
$notif = '';

if ($editID) {
    $queryEdit = mysqli_query($conn, "SELECT * FROM tb_penjualan WHERE penjualanID = $editID");
    $editData = mysqli_fetch_assoc($queryEdit);
    $barangLama = [];
    $queryBarangEdit = mysqli_query($conn, "SELECT * FROM tb_detailpenjualan WHERE penjualanID = $editID");
    while ($b = mysqli_fetch_assoc($queryBarangEdit)) {
        $barangLama[] = $b;
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $queryDetail = mysqli_query($conn, "SELECT * FROM tb_detailpenjualan WHERE penjualanID = $id");
    while ($d = mysqli_fetch_assoc($queryDetail)) {
        mysqli_query($conn, "UPDATE tb_barang SET stok = stok + {$d['jumlahproduk']} WHERE id = {$d['barangID']}");
    }
    mysqli_query($conn, "DELETE FROM tb_detailpenjualan WHERE penjualanID = $id");
    mysqli_query($conn, "DELETE FROM tb_penjualan WHERE penjualanID = $id");

    $notif = 'Transaksi berhasil dihapus.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $penjualanID = isset($_POST['penjualanID']) ? $_POST['penjualanID'] : null;
    $pelangganID = $_POST['pelangganID'];
    $tanggal = $_POST['tanggalpenjualan'];
    $barangID = isset($_POST['barangID']) ? $_POST['barangID'] : [];
    $jumlah = isset($_POST['jumlahproduk']) ? $_POST['jumlahproduk'] : [];

    $totalharga = 0;
    $stok_cek_lolos = true;
    $pesan_error = '';

    for ($i = 0; $i < count($barangID); $i++) {
        $id = $barangID[$i];
        $qty = $jumlah[$i];
        $stokQuery = mysqli_query($conn, "SELECT stok, nama FROM tb_barang WHERE id = $id");
        $data = mysqli_fetch_assoc($stokQuery);
        if ($data['stok'] < $qty) {
            $stok_cek_lolos = false;
            $pesan_error = "Stok barang '{$data['nama']}' tidak mencukupi.";
            break;
        }
    }

    if ($stok_cek_lolos) {
        if ($penjualanID) {
            $oldDetail = mysqli_query($conn, "SELECT * FROM tb_detailpenjualan WHERE penjualanID = $penjualanID");
            while ($d = mysqli_fetch_assoc($oldDetail)) {
                mysqli_query($conn, "UPDATE tb_barang SET stok = stok + {$d['jumlahproduk']} WHERE id = {$d['barangID']}");
            }
            mysqli_query($conn, "DELETE FROM tb_detailpenjualan WHERE penjualanID = $penjualanID");

            for ($i = 0; $i < count($barangID); $i++) {
                $id = $barangID[$i];
                $qty = $jumlah[$i];
                $harga = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga FROM tb_barang WHERE id = $id"))['harga'];
                $subtotal = $harga * $qty;
                $totalharga += $subtotal;

                mysqli_query($conn, "INSERT INTO tb_detailpenjualan (penjualanID, barangID, jumlahproduk, subtotal) VALUES ('$penjualanID', '$id', '$qty', '$subtotal')");
                mysqli_query($conn, "UPDATE tb_barang SET stok = stok - $qty WHERE id = $id");
            }

            mysqli_query($conn, "UPDATE tb_penjualan SET tanggalpenjualan='$tanggal', pelangganID='$pelangganID', totalharga='$totalharga' WHERE penjualanID=$penjualanID");

            header("Location: admin.php?page=datatransaksi&pesan=update");
            exit;
        } else {
            for ($i = 0; $i < count($barangID); $i++) {
                $id = $barangID[$i];
                $qty = $jumlah[$i];
                $harga = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga FROM tb_barang WHERE id = $id"))['harga'];
                $totalharga += $harga * $qty;
            }

            mysqli_query($conn, "INSERT INTO tb_penjualan (tanggalpenjualan, totalharga, pelangganID) VALUES ('$tanggal', '$totalharga', '$pelangganID')");
            $penjualanID = mysqli_insert_id($conn);

            for ($i = 0; $i < count($barangID); $i++) {
                $id = $barangID[$i];
                $qty = $jumlah[$i];
                $harga = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga FROM tb_barang WHERE id = $id"))['harga'];
                $subtotal = $harga * $qty;
                mysqli_query($conn, "INSERT INTO tb_detailpenjualan (penjualanID, barangID, jumlahproduk, subtotal) VALUES ('$penjualanID', '$id', '$qty', '$subtotal')");
                mysqli_query($conn, "UPDATE tb_barang SET stok = stok - $qty WHERE id = $id");
            }

            header("Location: admin.php?page=datatransaksi&pesan=input");
            exit;
        }
    }
}

if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] == 'update') {
        $notif = 'Data transaksi berhasil diupdate.';
    } elseif ($_GET['pesan'] == 'input') {
        $notif = 'Data transaksi berhasil disimpan.';
    }
}

$queryPelanggan = mysqli_query($conn, "SELECT * FROM tb_pelanggan");
$queryBarang = mysqli_query($conn, "SELECT * FROM tb_barang");
$queryTransaksi = mysqli_query($conn, "SELECT p.penjualanID, p.tanggalpenjualan, p.totalharga, pl.namapelanggan FROM tb_penjualan p JOIN tb_pelanggan pl ON p.pelangganID = pl.pelangganID ORDER BY p.penjualanID DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Data Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <?php if ($notif): ?>
    <?php $alertType = (strpos($notif, 'dihapus') !== false) ? 'danger' : 'success'; ?>
    <div id="notif" class="alert alert-<?= $alertType ?>"><?= $notif ?></div>
  <?php endif; ?>

  <h3><?= $editID ? 'Ubah' : 'Input' ?> Transaksi</h3>

  <form action="" method="post">
    <?php if ($editID): ?>
      <input type="hidden" name="penjualanID" value="<?= $editID ?>">
    <?php endif; ?>
    <div class="mb-3">
      <label>Pelanggan</label>
      <select name="pelangganID" class="form-select" required>
        <option value="">-- Pilih Pelanggan --</option>
        <?php while ($p = mysqli_fetch_assoc($queryPelanggan)) { ?>
          <option value="<?= $p['pelangganID'] ?>" <?= ($editData && $editData['pelangganID'] == $p['pelangganID']) ? 'selected' : '' ?>>
            <?= $p['namapelanggan'] ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Tanggal</label>
      <input type="date" name="tanggalpenjualan" class="form-control" value="<?= $editData ? $editData['tanggalpenjualan'] : '' ?>" required>
    </div>

    <h5>Barang yang Dibeli</h5>
    <div id="produk-wrapper">
      <?php
      if ($editID && count($barangLama) > 0) {
        foreach ($barangLama as $item) {
      ?>
        <div class="row g-3 align-items-end mb-2">
          <div class="col-md-6">
            <label>Barang</label>
            <select name="barangID[]" class="form-select" required>
              <option value="">-- Pilih Barang --</option>
              <?php mysqli_data_seek($queryBarang, 0); while ($b = mysqli_fetch_assoc($queryBarang)) { ?>
                <option value="<?= $b['id'] ?>" <?= $b['id'] == $item['barangID'] ? 'selected' : '' ?>>
                  <?= $b['nama'] ?> (Stok: <?= $b['stok'] ?>)
                </option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-4">
            <label>Jumlah</label>
            <input type="number" name="jumlahproduk[]" class="form-control" value="<?= $item['jumlahproduk'] ?>" required>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-success add-barang">+</button>
          </div>
        </div>
      <?php }} else { ?>
        <div class="row g-3 align-items-end mb-2">
          <div class="col-md-6">
            <label>Barang</label>
            <select name="barangID[]" class="form-select" required>
              <option value="">-- Pilih Barang --</option>
              <?php while($b = mysqli_fetch_assoc($queryBarang)) { ?>
                <option value="<?= $b['id'] ?>"><?= $b['nama'] ?> (Stok: <?= $b['stok'] ?>)</option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-4">
            <label>Jumlah</label>
            <input type="number" name="jumlahproduk[]" class="form-control" required>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-success add-barang">+</button>
          </div>
        </div>
      <?php } ?>
    </div>

    <button type="submit" class="btn btn-primary mt-3"><?= $editID ? 'Update' : 'Simpan' ?> Transaksi</button>
  </form>

  <hr class="my-5">
  <h3>Riwayat Transaksi</h3>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Total Harga</th>
        <th>Barang</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php $no=1; while ($tr = mysqli_fetch_assoc($queryTransaksi)) {
        $penjualanID = $tr['penjualanID'];
        $queryDetail = mysqli_query($conn, "SELECT db.nama, dp.jumlahproduk FROM tb_detailpenjualan dp JOIN tb_barang db ON dp.barangID = db.id WHERE dp.penjualanID = $penjualanID");
        $detailList = [];
        while ($d = mysqli_fetch_assoc($queryDetail)) {
            $detailList[] = "{$d['nama']} (x{$d['jumlahproduk']})";
        }
        $listBarang = implode(', ', $detailList);
      ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= $tr['tanggalpenjualan'] ?></td>
          <td><?= $tr['namapelanggan'] ?></td>
          <td>Rp <?= number_format($tr['totalharga'], 0, ',', '.') ?></td>
          <td><?= $listBarang ?></td>
          <td>
            <a href="?page=datatransaksi&edit=<?= $tr['penjualanID'] ?>" class="btn btn-sm btn-outline-warning me-1">Ubah</a>
            <a href="?page=datatransaksi&hapus=<?= $tr['penjualanID'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">Hapus</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<script>
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('add-barang')) {
    const row = e.target.closest('.row').cloneNode(true);
    row.querySelectorAll('input').forEach(input => input.value = '');
    document.querySelector('#produk-wrapper').appendChild(row);
  }
});
setTimeout(function() {
  const notif = document.getElementById('notif');
  if (notif) {
    notif.classList.add('fade');
    setTimeout(function() {
      notif.remove();
    }, 1000);
  }
}, 3000);
</script>
</body>
</html>
