<?php
require "checkpost.php";
require "../db/database.php";

$validated = false;
$donasiname = "";
$donasiid = null;
$success = false;

if (isset($_POST["donasiid"])) {
    $db = new Database();

    $donasiid = $_POST["donasiid"];
    $mahasiswaid = $_POST["mahasiswaid"];
    $pass = $_POST["password"];
    $amount = (int)$_POST["jumlah_donasi"];
    $private = (boolean)$_POST["private"];
    $donasiame = $_POST["donasiname"];

    $validated = $db->validatePassword($mahasiswaid, $pass);

    if ($validated && $amount >= 1000 && $amount <= $db->getUserById($mahasiswaid, PDO::FETCH_OBJ)->saldo) {
        if ($db->funddonasi($donasiid, $mahasiswaid, $amount, $private)) {
            $db->addTransaction($amount, "donasi", "keluar", $mahasiswaid, "direct", "Donasi $donasiname");
            $success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>

    <title>Donasi Sukses</title>
</head>

<body>
    <div class="container text-center mt-2">
        <div class="p-2 pt-1">
            <h1 class="card-title">Donasi <?= $success ?'Sukses!' : 'Gagal' ?></h1>
            <p class="card-text">Donasi kamu <?= $donasiame ? "untuk $donasiame" : "" ?> <?= $success ?'senilai ' . boldGreen(rupiah($amount)) : '' ?> telah <?= $success ?'sukses!' : 'gagal' ?>!</p>

            <?php 
                $satusState = $success;
                include "../component/statusIcon.php";
            ?>

            <?php if (!isset($_POST["donasiid"])) { ?>
                <p class="card-text">Sepertinya donasi kamu sudah masuk, kamu bisa meninggalkan halaman ini</p>
                <a href="../mahasiswa/donasi.php" role="button" class="btn btn-primary btn-lg">Kembali ke halaman list donasi</a>
            <?php } else if ($amount < 1000) { ?>
                <p class="card-text">Maaf, minimal donasi adalah <?=boldGreen(rupiah(1000))?></p>
                <a href="../mahasiswa/bayardonasi.php?payment_success=0&id_donasi=<?= $donasiid ?>" role="button" class="btn btn-primary btn-lg">Kembali ke halaman donasi</a>
            <?php } else if (!$validated) { ?>
                <p class="card-text">Terjadi kesalahan autentikasi</p>
                <a href="../mahasiswa/bayardonasi.php?payment_success=0&id_donasi=<?= $donasiid ?>" role="button" class="btn btn-primary btn-lg">Kembali ke halaman donasi</a>
            <?php } else { ?>
                <a href="../mahasiswa/bayardonasi.php?payment_success=1&id_donasi=<?= $donasiid ?>" role="button" class="btn btn-primary btn-lg">Kembali ke halaman donasi</a>
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