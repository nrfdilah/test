-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Apr 2019 pada 13.40
-- Versi server: 10.1.37-MariaDB
-- Versi PHP: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `popay`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL DEFAULT 'admin',
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `aktivitas_admin`
--

CREATE TABLE `aktivitas_admin` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_admin` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `nilai` bigint(20) NOT NULL,
  `ext_1` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `donasi`
--

CREATE TABLE `donasi` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` longtext NOT NULL,
  `diposting_oleh` int(11) NOT NULL COMMENT 'id admin',
  `target_donasi` bigint(20) NOT NULL,
  `terkumpul` bigint(20) NOT NULL DEFAULT '0',
  `status` enum('open','close') NOT NULL DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran_donasi`
--

CREATE TABLE `pembayaran_donasi` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `idadmin` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amout` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `qrcode`
--

CREATE TABLE `qrcode` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `unique_id` varchar(255) NOT NULL COMMENT 'Nilai QR Code',
  `judul` varchar(255) NOT NULL,
  `tetap` tinyint(1) NOT NULL,
  `dibuat_oleh` int(11) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_kantin` int(11) NOT NULL,
  `nilai` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jurusan`
--

CREATE TABLE `jurusan` (
  `id` int(11) NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `bidang_studi` varchar(255) NOT NULL COMMENT 'Bidang Studi Rekayasa/Tata Niaga',
  `program_studi` varchar(255) NOT NULL COMMENT 'TKJ/Teknik Telekomunikasi/dll',
  `nama_jurusan` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `biaya_ukt` bigint(20) NOT NULL,
  `saldo` bigint(20) NOT NULL,
  `kode` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ukt`
--

CREATE TABLE `ukt` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `id_mahasiswa` int(11) NOT NULL,
  `bulan` enum('januari','februari','maret','april','mei','juni','juli','agustus','september','oktober','november','desember') NOT NULL,
  `status_pembayaran` tinyint(1) DEFAULT '0',
  `tanggal_pembayaran` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kantin`
--

CREATE TABLE `kantin` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` mediumtext NOT NULL,
  `saldo` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_kantin`
--

CREATE TABLE `transaksi_kantin` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `kantin_id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `qr_id` int(11) NOT NULL,
  `jumlah` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `tanggal_pendaftaran` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan') NOT NULL,
  `email` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL DEFAULT 'mahasiswa',
  `jenjang` enum('D3','D4') NOT NULL,
  `prodi` varchar(255) NOT NULL,
  `kelas` varchar(255) NOT NULL,
  `nim` varchar(255) NOT NULL,
  `saldo` bigint(20) NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `donasi_mahasiswa`
--

CREATE TABLE `donasi_mahasiswa` (
  `id` int(11) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_donasi` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `jumlah` bigint(20) NOT NULL,
  `private` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_mahasiswa`
--

CREATE TABLE `transaksi_mahasiswa` (
  `id` bigint(20) NOT NULL,
  `id_jurusan` int(11) NOT NULL,
  `kredit` bigint(20) NOT NULL,
  `debit` bigint(20) NOT NULL,
  `tipe` varchar(255) NOT NULL COMMENT 'tipe transaksi (UKT/donasi/dll)',
  `jenis` enum('masuk','keluar') NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'tanggal transaksi',
  `mahasiswa_id` int(11) NOT NULL,
  `metode` varchar(255) NOT NULL COMMENT 'metode pembayaran (QR Code/NIM)',
  `deskripsi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indeks untuk tabel `aktivitas_admin`
--
ALTER TABLE `aktivitas_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jurusan` (`id_jurusan`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `diposting_oleh` (`diposting_oleh`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indeks untuk tabel `pembayaran_donasi`
--
ALTER TABLE `pembayaran_donasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idadmin` (`idadmin`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indeks untuk tabel `qrcode`
--
ALTER TABLE `qrcode`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`),
  ADD KEY `dibuat_oleh` (`dibuat_oleh`),
  ADD KEY `id_kantin` (`id_kantin`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indeks untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ukt`
--
ALTER TABLE `ukt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jurusan` (`id_jurusan`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`);

--
-- Indeks untuk tabel `kantin`
--
ALTER TABLE `kantin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indeks untuk tabel `transaksi_kantin`
--
ALTER TABLE `transaksi_kantin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indeks untuk tabel `donasi_mahasiswa`
--
ALTER TABLE `donasi_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_donasi` (`id_donasi`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- Indeks untuk tabel `transaksi_mahasiswa`
--
ALTER TABLE `transaksi_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `id_jurusan` (`id_jurusan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `aktivitas_admin`
--
ALTER TABLE `aktivitas_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `donasi`
--
ALTER TABLE `donasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pembayaran_donasi`
--
ALTER TABLE `pembayaran_donasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `qrcode`
--
ALTER TABLE `qrcode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ukt`
--
ALTER TABLE `ukt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kantin`
--
ALTER TABLE `kantin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaksi_kantin`
--
ALTER TABLE `transaksi_kantin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `donasi_mahasiswa`
--
ALTER TABLE `donasi_mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaksi_mahasiswa`
--
ALTER TABLE `transaksi_mahasiswa`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`);

--
-- Ketidakleluasaan untuk tabel `aktivitas_admin`
--
ALTER TABLE `aktivitas_admin`
  ADD CONSTRAINT `aktivitas_admin_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`),
  ADD CONSTRAINT `aktivitas_admin_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id`);

--
-- Ketidakleluasaan untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `donasi_ibfk_1` FOREIGN KEY (`diposting_oleh`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `donasi_ibfk_2` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`);

--
-- Ketidakleluasaan untuk tabel `pembayaran_donasi`
--
ALTER TABLE `pembayaran_donasi`
  ADD CONSTRAINT `pembayaran_donasi_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`),
  ADD CONSTRAINT `pembayaran_donasi_ibfk_2` FOREIGN KEY (`idadmin`) REFERENCES `admin` (`id`);

--
-- Ketidakleluasaan untuk tabel `qrcode`
--
ALTER TABLE `qrcode`
  ADD CONSTRAINT `qrcode_ibfk_1` FOREIGN KEY (`id_kantin`) REFERENCES `kantin` (`id`),
  ADD CONSTRAINT `qrcode_ibfk_2` FOREIGN KEY (`dibuat_oleh`) REFERENCES `admin` (`id`),
  ADD CONSTRAINT `qrcode_ibfk_3` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`);

--
-- Ketidakleluasaan untuk tabel `ukt`
--
ALTER TABLE `ukt`
  ADD CONSTRAINT `ukt_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`),
  ADD CONSTRAINT `ukt_ibfk_2` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id`);

--
-- Ketidakleluasaan untuk tabel `kantin`
--
ALTER TABLE `kantin`
  ADD CONSTRAINT `kantin_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`);

--
-- Ketidakleluasaan untuk tabel `transaksi_kantin`
--
ALTER TABLE `transaksi_kantin`
  ADD CONSTRAINT `transaksi_kantin_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`);

--
-- Ketidakleluasaan untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`);

--
-- Ketidakleluasaan untuk tabel `donasi_mahasiswa`
--
ALTER TABLE `donasi_mahasiswa`
  ADD CONSTRAINT `donasi_mahasiswa_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`),
  ADD CONSTRAINT `donasi_mahasiswa_ibfk_2` FOREIGN KEY (`id_donasi`) REFERENCES `donasi` (`id`),
  ADD CONSTRAINT `donasi_mahasiswa_ibfk_3` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`);

--
-- Ketidakleluasaan untuk tabel `transaksi_mahasiswa`
--
ALTER TABLE `transaksi_mahasiswa`
  ADD CONSTRAINT `transaksi_mahasiswa_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`),
  ADD CONSTRAINT `transaksi_mahasiswa_ibfk_2` FOREIGN KEY (`id_jurusan`) REFERENCES `jurusan` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
