<?php
include 'lib/koneksi.php';

// Total Data Barang
$stmt_barang = $conn->prepare("SELECT * FROM tb_barang");
$stmt_barang->execute();
$total_barang = $stmt_barang->rowCount();

// Total Data Pelanggan
$stmt_stok = $conn->prepare("SELECT COUNT(*) AS total_pelanggan FROM tb_pelanggan");
$stmt_stok->execute();
$stok_data = $stmt_stok->fetch(PDO::FETCH_ASSOC);
$total_pelanggan = $stok_data['total_pelanggan'] ?? 0;

// Total Data Transaksi
$stmt_transaksi = $conn->prepare("SELECT COUNT(*) AS total_transaksi FROM tb_penjualan");
$stmt_transaksi->execute();
$transaksi_data = $stmt_transaksi->fetch(PDO::FETCH_ASSOC);
$total_transaksi = $transaksi_data['total_transaksi'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Inventory</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    .full-height {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .main-content {
      flex: 1;
      display: flex;
    }

    .sidebar {
      width: 230px;
      background: linear-gradient(to bottom, #212529, #343a40);
      color: white;
      padding: 20px;
      min-height: 100vh;
    }

    .sidebar h5 {
      font-weight: 600;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      margin: 10px 0;
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 12px;
      border-radius: 6px;
      transition: background-color 0.2s ease-in-out;
    }

    .sidebar a:hover {
      background-color: #495057;
      transform: translateX(4px);
    }

    .sidebar strong {
      font-size: 13px;
      color: #adb5bd;
      margin-top: 20px;
      display: block;
    }

    .card-info {
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s;
      height: 100%;
      padding: 20px;
    }

    .card-info:hover {
      transform: translateY(-5px);
    }

    .card-icon {
      font-size: 36px;
      opacity: 0.8;
    }

    .alert-info {
      border-left: 5px solid #0dcaf0;
      border-radius: 8px;
      font-weight: 500;
    }

    h2 {
      font-weight: 600;
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }
    }
  </style>
</head>

<body class="full-height">
  <div class="main-content">
    <!-- Sidebar -->
    <div class="sidebar">
      <h5 class="mb-3 text-white">
        <i class="fas fa-box-open me-2"></i> STOK BARANG
      </h5>
      <input type="text" class="form-control mb-3" placeholder="Search" />
      <a href="admin.php?page=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <strong>Data Master</strong>
      <a href="admin.php?page=databarang"><i class="fas fa-box"></i> Data Barang</a>
      <strong>Transaksi</strong>
      <a href="admin.php?page=datapelanggan"><i class="fas fa-users"></i> Data Pelanggan</a>
      <a href="admin.php?page=datatransaksi"><i class="fas fa-receipt"></i> Data Transaksi</a>
      <strong>Laporan</strong>
      <a href="admin.php?page=laporan"><i class="fas fa-users"></i> Laporan Transaksi</a>
      <strong>Setting</strong>
      <a href="admin.php?page=datapetugas"><i class="fas fa-user-cog"></i> Data Petugas</a>
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
      <?php
      $page = $_GET['page'] ?? 'dashboard';

      switch ($page) {
        case 'databarang':
          include 'page/databarang.php';
          break;
        case 'tambahbarang':
          include 'page/tambahbarang.php';
          break;
        case 'datapelanggan':
          include 'page/datapelanggan.php';
          break;
        case 'datatransaksi':
          include 'page/datatransaksi.php';
          break;
        case 'datapetugas':
          include 'page/datapetugas.php';
          break;
        case 'laporan':
          include 'page/laporan.php';
          break;
        case 'dashboard':
      ?>
          <h2 class="mb-4">Dashboard</h2>

          <!-- Cards -->
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <a href="admin.php?page=databarang" style="text-decoration: none;">
                <div class="bg-info text-white card-info d-flex justify-content-between align-items-center">
                  <div>
                    <h3><?= $total_barang ?></h3>
                    <p>Total Data Barang</p>
                  </div>
                  <i class="fas fa-shopping-bag card-icon"></i>
                </div>
              </a>
            </div>
            <div class="col-md-4">
              <a href="admin.php?page=datapelanggan" style="text-decoration: none;">
                <div class="bg-warning text-white card-info d-flex justify-content-between align-items-center">
                  <div>
                    <h3><?= $total_pelanggan ?></h3>
                    <p>Data Pelanggan</p>
                  </div>
                  <i class="fas fa-user card-icon"></i>
                </div>
              </a>
            </div>
            <div class="col-md-4">
              <a href="admin.php?page=datatransaksi" style="text-decoration: none;">
                <div class="bg-danger text-white card-info d-flex justify-content-between align-items-center">
                  <div>
                    <h3><?= $total_transaksi ?></h3>
                    <p>Data Transaksi</p>
                  </div>
                  <i class="fas fa-chart-pie card-icon"></i>
                </div>
              </a>
            </div>
          </div>

          <!-- Welcome Message -->
          <div class="alert alert-info">
            <strong>Selamat Datang Admin</strong><br />
            Gunakan sistem stok barang untuk mengatur persediaan barang.
          </div>
      <?php
          break;
        default:
          echo "<p>Halaman tidak ditemukan!</p>";
          break;
      }
      ?>
    </div>
  </div>
</body>
</html>
