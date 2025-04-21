-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 18 Apr 2025 pada 10.15
-- Versi server: 8.0.17
-- Versi PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbstokbarang`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_barang`
--

CREATE TABLE `tb_barang` (
  `id` int(11) NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `stok` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `gambar` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tb_barang`
--

INSERT INTO `tb_barang` (`id`, `kode`, `nama`, `stok`, `harga`, `gambar`) VALUES
(15, '9898', 'boneka', 0, 20000, 'img_67fd065c1e8032.02826184.webp'),
(17, '2222', 'buku', 1, 20000, 'img_67fd06c4eab359.79979885.jpg'),
(23, '89000', 'buku cerita', 2, 40000, 'img_67fdb05a9da874.14056536.jpg'),
(24, '80000', 'foto strip', 2, 50000, 'img_67fdb0a79ded78.74675316.jpg'),
(25, '9000000', 'buku pulang', 1, 50000, 'img_67fdb0f4334485.38030533.jpeg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_detailpenjualan`
--

CREATE TABLE `tb_detailpenjualan` (
  `detailID` int(11) NOT NULL,
  `penjualanID` int(11) NOT NULL,
  `barangID` int(11) NOT NULL,
  `jumlahproduk` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pelanggan`
--

CREATE TABLE `tb_pelanggan` (
  `pelangganID` int(11) NOT NULL,
  `namapelanggan` varchar(225) NOT NULL,
  `alamat` text NOT NULL,
  `nomortelepon` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tb_pelanggan`
--

INSERT INTO `tb_pelanggan` (`pelangganID`, `namapelanggan`, `alamat`, `nomortelepon`) VALUES
(17, 'azzahra', 'jl at taqwa depok', '089090'),
(21, 'syakia', 'jln cikaret', '89089'),
(22, 'ale', 'cimpaen bogor', '234567'),
(23, 'salwa', 'bogor sentul', '345678'),
(24, 'albira', 'depok cilodong', '456789'),
(25, 'suci', 'denpasar bali', '4667788'),
(26, 'reja', 'clodong', '12345678909');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_penjualan`
--

CREATE TABLE `tb_penjualan` (
  `penjualanID` int(11) NOT NULL,
  `tanggalpenjualan` date NOT NULL,
  `totalharga` decimal(10,2) NOT NULL,
  `pelangganID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tb_penjualan`
--

INSERT INTO `tb_penjualan` (`penjualanID`, `tanggalpenjualan`, `totalharga`, `pelangganID`) VALUES
(15, '2025-04-26', '20000.00', 17),
(19, '2025-04-19', '30000.00', 21),
(20, '2025-04-20', '40000.00', 22),
(21, '2025-04-21', '40000.00', 24),
(22, '2025-04-15', '50000.00', 26);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_produk`
--

CREATE TABLE `tb_produk` (
  `produkID` int(11) NOT NULL,
  `namaproduk` varchar(225) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `transaksiID` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `idpelanggan` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`transaksiID`, `tanggal`, `idpelanggan`, `total`) VALUES
(1, '2025-04-17', 1, '20000.00'),
(2, '2025-04-16', 3, '100000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `userID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `level` enum('petugas','admin') NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`userID`, `username`, `password`, `level`) VALUES
(2, 'petugas', 'petugas123', 'petugas'),
(3, 'admin', 'ZAHRA123', 'admin'),
(4, 'azahra', 'albira12345', 'admin'),
(5, 'azahra', 'albira12345', 'admin'),
(6, 'azahra', 'albira12345', 'admin'),
(7, 'root', 'albira12345', 'admin'),
(8, 'salwa', '123', 'admin'),
(9, 'ara', '021', 'admin'),
(11, 'zahra', '1234', 'petugas'),
(12, 'albira', '12345', 'petugas'),
(13, 'albira123', '123', 'petugas'),
(14, 'putri', 'putri12345', 'petugas'),
(15, 'zar', 'zar1234', 'petugas');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_barang`
--
ALTER TABLE `tb_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_detailpenjualan`
--
ALTER TABLE `tb_detailpenjualan`
  ADD PRIMARY KEY (`detailID`),
  ADD KEY `produkID` (`barangID`),
  ADD KEY `penjualanID` (`penjualanID`);

--
-- Indeks untuk tabel `tb_pelanggan`
--
ALTER TABLE `tb_pelanggan`
  ADD PRIMARY KEY (`pelangganID`);

--
-- Indeks untuk tabel `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  ADD PRIMARY KEY (`penjualanID`),
  ADD KEY `pelangganID` (`pelangganID`);

--
-- Indeks untuk tabel `tb_produk`
--
ALTER TABLE `tb_produk`
  ADD PRIMARY KEY (`produkID`);

--
-- Indeks untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`transaksiID`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_barang`
--
ALTER TABLE `tb_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `tb_detailpenjualan`
--
ALTER TABLE `tb_detailpenjualan`
  MODIFY `detailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `tb_pelanggan`
--
ALTER TABLE `tb_pelanggan`
  MODIFY `pelangganID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  MODIFY `penjualanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `tb_produk`
--
ALTER TABLE `tb_produk`
  MODIFY `produkID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `transaksiID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_detailpenjualan`
--
ALTER TABLE `tb_detailpenjualan`
  ADD CONSTRAINT `tb_detailpenjualan_ibfk_1` FOREIGN KEY (`penjualanID`) REFERENCES `tb_penjualan` (`penjualanID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `tb_detailpenjualan_ibfk_2` FOREIGN KEY (`barangID`) REFERENCES `tb_produk` (`produkID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  ADD CONSTRAINT `tb_penjualan_ibfk_1` FOREIGN KEY (`pelangganID`) REFERENCES `tb_pelanggan` (`pelangganID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
