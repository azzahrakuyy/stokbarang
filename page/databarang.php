<?php
// Koneksi ke database
try {
    $conn = new PDO('mysql:host=localhost;dbname=dbstokbarang', 'root', 'albira12345');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Proses untuk menambah barang
    if (isset($_POST['tambah_barang'])) {
        $kode = $_POST['kode'];
        $nama = $_POST['nama'];
        $stok = $_POST['stok'];
        $harga = $_POST['harga'];
        $gambar = $_FILES['gambar'];

        if ($gambar['error'] == 0) {
            $target_dir = "../asset/upload/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $ext = pathinfo($gambar['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid('img_', true) . '.' . $ext;
            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($gambar["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("INSERT INTO tb_barang (kode, nama, stok, harga, gambar) VALUES (:kode, :nama, :stok, :harga, :gambar)");
                $stmt->bindParam(':kode', $kode);
                $stmt->bindParam(':nama', $nama);
                $stmt->bindParam(':stok', $stok);
                $stmt->bindParam(':harga', $harga);
                $stmt->bindParam(':gambar', $new_filename);
                $stmt->execute();

                header("Location: ?page=databarang&status=tambah_sukses");
                exit;
            } else {
                echo "Gagal upload gambar!";
            }
        } else {
            echo "Upload error code: " . $gambar['error'];
        }
    }

    // Proses untuk menghapus barang
    if (isset($_GET['hapus'])) {
        $id = $_GET['hapus'];
        $stmt = $conn->prepare("SELECT gambar FROM tb_barang WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $barang = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($barang) {
            $gambar = $barang['gambar'];

            if ($gambar && file_exists("../asset/upload/" . $gambar)) {
                unlink("../asset/upload/" . $gambar);
            }

            $stmt = $conn->prepare("DELETE FROM tb_barang WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            header("Location: ?page=databarang&status=hapus_sukses");
            exit;
        }
    }

    // Proses untuk mengedit barang
    if (isset($_GET['edit'])) {
        $id = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM tb_barang WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $barang = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$barang) {
            echo "Barang tidak ditemukan.";
            exit;
        }

        if (isset($_POST['edit_barang'])) {
            $kode = $_POST['kode'];
            $nama = $_POST['nama'];
            $stok = $_POST['stok'];
            $harga = $_POST['harga'];
            $gambar = $_FILES['gambar'];

            if ($gambar['error'] == 0) {
                $target_dir = "../asset/upload/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $ext = pathinfo($gambar['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid('img_', true) . '.' . $ext;
                $target_file = $target_dir . $new_filename;

                if (file_exists("../asset/upload/" . $barang['gambar'])) {
                    unlink("../asset/upload/" . $barang['gambar']);
                }

                if (move_uploaded_file($gambar["tmp_name"], $target_file)) {
                    $gambar = $new_filename;
                } else {
                    echo "Gagal upload gambar!";
                    exit;
                }
            } else {
                $gambar = $barang['gambar'];
            }

            $stmt = $conn->prepare("UPDATE tb_barang SET kode = :kode, nama = :nama, stok = :stok, harga = :harga, gambar = :gambar WHERE id = :id");
            $stmt->bindParam(':kode', $kode);
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':stok', $stok);
            $stmt->bindParam(':harga', $harga);
            $stmt->bindParam(':gambar', $gambar);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            header("Location: ?page=databarang&status=edit_sukses");
            exit;
        }
    }

    // Ambil semua data barang, urutkan berdasarkan id DESC (terbaru di atas)
    $stmt = $conn->prepare("SELECT * FROM tb_barang ORDER BY id DESC");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Barang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3 class="mb-4">Data Barang</h3>

    <!-- Notifikasi -->
    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] == 'hapus_sukses'): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Data berhasil dihapus.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['status'] == 'tambah_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Data berhasil ditambahkan.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif ($_GET['status'] == 'edit_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Data berhasil diupdate.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Form Tambah / Edit -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <?= isset($_GET['edit']) ? 'Edit Barang' : 'Tambah Barang' ?>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="kode">Kode</label>
                    <input type="text" id="kode" name="kode" class="form-control" value="<?= isset($barang['kode']) ? htmlspecialchars($barang['kode']) : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" value="<?= isset($barang['nama']) ? htmlspecialchars($barang['nama']) : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="stok">Stok</label>
                    <input type="number" id="stok" name="stok" class="form-control" value="<?= isset($barang['stok']) ? htmlspecialchars($barang['stok']) : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="harga">Harga</label>
                    <input type="number" id="harga" name="harga" class="form-control" value="<?= isset($barang['harga']) ? htmlspecialchars($barang['harga']) : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label for="gambar">Gambar</label>
                    <input type="file" id="gambar" name="gambar" class="form-control" accept="image/*">
                    <?php if (isset($barang['gambar'])): ?>
                        <img src="../asset/upload/<?= htmlspecialchars($barang['gambar']) ?>" class="img-thumbnail mt-2" style="max-width: 100px;">
                    <?php endif; ?>
                </div>
                <button type="submit" name="<?= isset($_GET['edit']) ? 'edit_barang' : 'tambah_barang' ?>" class="btn btn-primary">
                    <?= isset($_GET['edit']) ? 'Update Barang' : 'Tambah Barang' ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">Daftar Barang</div>
        <div class="card-body">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Stok</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php $no = 1; foreach ($data as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <?php if ($row['gambar']): ?>
                                        <img src="../asset/upload/<?= htmlspecialchars($row['gambar']) ?>" class="img-thumbnail" style="max-width: 100px;">
                                    <?php else: ?>
                                        <span class="text-muted">Tidak Ada Gambar</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['kode']) ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['stok']) ?></td>
                                <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <a href="?page=databarang&edit=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning">Ubah</a>
                                    <a href="?page=databarang&hapus=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Tidak ada data untuk ditampilkan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
