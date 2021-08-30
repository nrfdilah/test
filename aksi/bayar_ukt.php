<?php
require "checkpost.php";
require "../db/database.php";

$validated = false;
$success = false;

if (isset($_POST["idukt"])) {
    $db = new Database();

    $idukt = $_POST["idukt"];
    $idjurusan = $_POST["idjurusan"];
    $bulan = $_POST["bulanukt"];
    $idmahasiswa = $_POST["idmahasiswa"];
    $pass = $_POST["password"];

    $validated = $db->validatePassword($idmahasiswa, $pass);

    $mahasiswa = $db->getUserById($idmahasiswa, PDO::FETCH_OBJ);
    $jurusan = $db->getJurusanData($mahasiswa->id_jurusan, PDO::FETCH_OBJ);

    if ($validated && $mahasiswa->saldo >= $jurusan->biaya_ukt) {
        if ($db->payUKT($idmahasiswa, $idjurusan, $idukt)) {
            $db->addTransaction($jurusan->biaya_ukt, "ukt", "keluar", $idmahasiswa, "direct", "Pembayaran UKT Bulan ".ucwords($bulan));
            $success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>

    <title>UKT <?= $success ?'Sukses!' : 'Gagal' ?></title>
</head>

<body>
    <div class="container text-center mt-2">
        <div class="p-2 pt-1">
            <h1 class="card-title">Pembayaran UKT <?= $success ?'Sukses!' : 'Gagal' ?></h1>

            <?php 
                $satusState = $success;
                include "../component/statusIcon.php";
            ?>

            <?php if (!isset($_POST["idukt"])) { ?>
                <p class="card-text">Sepertinya UKT kamu sudah masuk, kamu bisa meninggalkan halaman ini</p>
                <a href="../mahasiswa/ukt.php" role="button" class="btn btn-primary btn-lg">Kembali ke halaman UKT</a>
            <?php } else if ($mahasiswa->saldo < $jurusan->biaya_ukt) { ?>
                <p class="card-text">Maaf, saldo anda tidak mencukupi untuk membayar UKT</p>
                <a href="../mahasiswa/ukt.php?payment_success=0&id_UKT=<?= $idukt ?>" role="button" class="btn btn-primary btn-lg">Kembali ke halaman UKT</a>
            <?php } else if (!$validated) { ?>
                <p class="card-text">Terjadi kesalahan autentikasi</p>
                <a href="../mahasiswa/ukt.php?payment_success=0&id_UKT=<?= $idukt ?>" role="button" class="btn btn-primary btn-lg">Kembali ke halaman UKT</a>
            <?php } else { ?>
            <p class="card-text">Pembayaran UKT <?= $success ?'senilai ' . boldGreen(rupiah($jurusan->biaya_ukt)) : '' ?> telah <?= $success ?'sukses!' : 'gagal' ?>!</p>
                <a href="../mahasiswa/ukt.php?payment_success=1&id_UKT=<?= $idukt ?>" role="button" class="btn btn-primary btn-lg">Kembali ke halaman UKT</a>
            <?php } ?>

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