<?php
include 'lib/koneksi.php';
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
      <a href="petugas.php?page=dashboard" class="<?= ($page == 'dashboard') ? 'active wave-effect' : 'wave-effect' ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
      <strong>Transaksi</strong>
      <a href="petugas.php?page=datapelanggan" class="<?= ($page == 'datapelanggan') ? 'active wave-effect' : 'wave-effect' ?>"><i class="fas fa-users me-2"></i>Data Pelanggan</a>
      <a href="petugas.php?page=datatransaksi" class="<?= ($page == 'datatransaksi') ? 'active wave-effect' : 'wave-effect' ?>"><i class="fas fa-exchange-alt me-2"></i>Data Transaksi</a>
      <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
      <?php
      $page = $_GET['page'] ?? 'dashboard';

      switch ($page) {
        
        case 'datapelanggan':
          include 'page/datapelanggan.php';
          break;

        case 'datatransaksi':
          include 'page/datatransaksi.php';
          break;

        case 'dashboard':
          // Ambil data
          $stmt_stok = $conn->prepare("SELECT COUNT(*) AS total_pelanggan FROM tb_pelanggan");
          $stmt_stok->execute();
          $stok_data = $stmt_stok->fetch(PDO::FETCH_ASSOC);
          $total_pelanggan = $stok_data['total_pelanggan'] ?? 0;

          $stmt_transaksi = $conn->prepare("SELECT COUNT(*) AS total_transaksi FROM tb_penjualan");
          $stmt_transaksi->execute();
          $transaksi_data = $stmt_transaksi->fetch(PDO::FETCH_ASSOC);
          $total_transaksi = $transaksi_data['total_transaksi'] ?? 0;
      ?>

      <h2 class="mb-4">Dashboard</h2>

      <!-- Cards -->
      <div class="row g-4 mb-4">
        <div class="col-md-6">
          <div class="bg-warning text-white card-info d-flex justify-content-between align-items-center">
            <div>
              <h3><?= $total_pelanggan ?></h3>
              <p>Data Pelanggan</p>
            </div>
            <i class="fas fa-user card-icon"></i>
          </div>
        </div>
        <div class="col-md-6">
          <div class="bg-success text-white card-info d-flex justify-content-between align-items-center">
            <div>
              <h3><?= $total_transaksi ?></h3>
              <p>Data Transaksi</p>
            </div>
            <i class="fas fa-exchange-alt card-icon"></i>
          </div>
        </div>
      </div>

      <!-- Welcome -->
      <div class="alert alert-info">
        <strong>Selamat Datang Petugas!</strong><br>
        Gunakan sistem stok barang untuk mengatur persediaan barang dengan efisien.
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
