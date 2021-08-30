<?php
require "checkpost.php";
require "../db/database.php";

$success = false;
$trx = null;

if (isset($_POST["nim"])) {
  $db = new Database();

  $nim = $_POST["nim"];
  $mahasiswaid = $_POST["mahasiswaid"];
  $amount = (int)$_POST["nominal"];

  $mahasiswa = $db->getUserById($mahasiswaid, PDO::FETCH_OBJ);
  $mahasiswa_tujuan = $db->getUserBynim($nim, PDO::FETCH_OBJ);

  $diri_sendiri = $nim == $mahasiswa->nim;

  if ($mahasiswa_tujuan && $mahasiswa_tujuan->id_jurusan == $mahasiswa->id_jurusan && !$diri_sendiri && $amount <= $mahasiswa->saldo && $amount >= 500) {
    if ($db->transferByNIM($mahasiswaid, $nim, $amount)) {
      $db->addTransaction(
          $amount, "transfer", "keluar", 
          $mahasiswaid, isset($_POST["metode"]) ? $_POST["metode"] : "qrcode", 
          "Transfer ke $mahasiswa_tujuan->nama"
        );

      $id = $db->addTransaction(
          $amount, "transfer", "masuk", 
          $mahasiswa_tujuan->id, isset($_POST["metode"]) ? $_POST["metode"] : "qrcode", 
          "Transfer dari $mahasiswa->nama"
        );

        $trx = $db->getTransaction($id, PDO::FETCH_OBJ);
      
      $success = true;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "../component/things.php" ?>

  <title>Transfer Sukses</title>
</head>

<body>
  <div class="container text-center mt-2">
    <div class="p-2 pt-1">
      <h1 class="card-title text-<?= $success ? "success" : "danger" ?>">Transfer <?= $success ?'Sukses!' : 'Gagal' ?></h1>
      <p class="card-text lead"><?= $success ? "$mahasiswa->nama <i class='fas fa-arrow-right'></i> $mahasiswa_tujuan->nama" : ""?></p>
      <p class="card-text"><?= $success ? $trx->tanggal : ""?></p>
      
      <?php 
        $satusState = $success;
        include "../component/statusIcon.php";
      ?>

      <?php if (!isset($_POST["nim"])) { ?>
        <p class="card-text">Sepertinya proses transfer telah berhasil sebelumnya</p>
      <?php } else if ($diri_sendiri) { ?>
          <p class="card-text">Tidak dapat mentransfer ke akun sendiri</p>
      <?php } else if (!$mahasiswa_tujuan) { ?>
          <p class="card-text">Mahasiswa dengan NIM <?=boldGreen($nim)?> tidak ditemukan, harap periksa kembali</p>
      <?php } else if (!$success) { ?>
        <p class="card-text">Terjadi kesalahan<br/><?= $amount > $mahasiswa->saldo ? "Saldo anda tidak mencukupi" : ($amount < 500 ? "Minimal transfer adalah ".boldGreen(rupiah(500)): "") ?></p>
      <?php } else if ($mahasiswa_tujuan->id_jurusan != $mahasiswa->id_jurusan) { ?>
        <p class="card-text">Tidak dapat mentransfer ke jurusan lain</p>
      <?php } else { ?>
      <p class="card-text">Transfer <?= $success ?'senilai ' . boldGreen(rupiah($amount)) . ' ke <span class="text-muted">' . $mahasiswa_tujuan->nim . "</span>": '' ?> telah <?= $success ?'sukses!' : 'gagal' ?>!</p>
      <?php } ?>
      
      <a href="../mahasiswa/kirim.php" role="button" class="btn btn-primary btn-lg">Kembali ke halaman transfer</a>
    </div>
  </div>
  <?php include "../component/scripts.php" ?>
  

  <script>
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</body>

</html>