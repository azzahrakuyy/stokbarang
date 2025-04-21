<?php
include 'lib/koneksi.php';

// Default nilai form
$edit = false;
$pelangganID = '';
$namapelanggan = '';
$alamat = '';
$nomortelepon = '';

// Ambil data jika klik "Ubah"
if (isset($_GET['edit'])) {
    $edit = true;
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM tb_pelanggan WHERE pelangganID = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $pelangganID = $data['pelangganID'];
        $namapelanggan = $data['namapelanggan'];
        $alamat = $data['alamat'];
        $nomortelepon = $data['nomortelepon'];
    }
}

// Proses simpan/update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namapelanggan = $_POST['namapelanggan'];
    $alamat = $_POST['alamat'];
    $nomortelepon = $_POST['nomortelepon'];

    if (isset($_POST['update'])) {
        $stmt = $conn->prepare("UPDATE tb_pelanggan SET namapelanggan=?, alamat=?, nomortelepon=? WHERE pelangganID=?");
        $stmt->execute([$namapelanggan, $alamat, $nomortelepon, $_POST['pelangganID']]);
        echo "<div class='alert alert-success'>✅ Data pelanggan berhasil diperbarui.</div>";
        echo "<meta http-equiv='refresh' content='1;url=?page=datapelanggan'>";
    } else {
        $stmt = $conn->prepare("INSERT INTO tb_pelanggan (namapelanggan, alamat, nomortelepon) VALUES (?, ?, ?)");
        $stmt->execute([$namapelanggan, $alamat, $nomortelepon]);
        echo "<div class='alert alert-success'>✅ Data pelanggan berhasil disimpan.</div>";
        echo "<meta http-equiv='refresh' content='1;url=?page=datapelanggan'>";
    }
}

// Proses hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Hapus data yang berhubungan dengan pelanggan dari tabel tb_penjualan
    $stmt = $conn->prepare("DELETE FROM tb_penjualan WHERE pelangganID = ?");
    $stmt->execute([$id]);

    // Hapus data pelanggan
    $stmt = $conn->prepare("DELETE FROM tb_pelanggan WHERE pelangganID = ?");
    $stmt->execute([$id]);

    echo "<div class='alert alert-danger'>🗑️ Data pelanggan berhasil dihapus.</div>";
    echo "<meta http-equiv='refresh' content='1;url=?page=datapelanggan'>";
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-4">
    <h3 class="mb-4">Data Pelanggan</h3>
    <!-- Form Pelanggan -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><?= $edit ? 'Edit' : 'Tambah' ?> Pelanggan</h5>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <?php if ($edit): ?>
                    <input type="hidden" name="pelangganID" value="<?= $pelangganID ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label">Nama Pelanggan</label>
                    <input type="text" name="namapelanggan" class="form-control" required value="<?= $namapelanggan ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" required><?= $alamat ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="text" name="nomortelepon" class="form-control" required value="<?= $nomortelepon ?>">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" name="<?= $edit ? 'update' : 'simpan' ?>" class="btn btn-<?= $edit ? 'warning' : 'success' ?>">
                        <?= $edit ? 'Update' : 'Simpan' ?>
                    </button>
                    <?php if ($edit): ?>
                        <a href="admin.php?page=pelanggan" class="btn btn-secondary">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Data Pelanggan</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Ambil data dengan urutan pelangganID DESC agar data terbaru berada di atas
                    $data = $conn->query("SELECT * FROM tb_pelanggan ORDER BY pelangganID DESC");
                    foreach ($data as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['namapelanggan'] ?></td>
                            <td><?= $row['alamat'] ?></td>
                            <td><?= $row['nomortelepon'] ?></td>
                            <td>
                                <a href="?page=datapelanggan&edit=<?= $row['pelangganID'] ?>" class="btn btn-sm btn-outline-warning me-1">Ubah</a>
                                <a href="?page=datapelanggan&hapus=<?= $row['pelangganID'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
