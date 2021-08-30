<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>
    <title>Transfer</title>
</head>

<body>
    <?php include "../process/getLoginData.php" ?>
    <?php include "../component/mahasiswa/sidebaropen.php" ?>

    <div class="text-center">

    <?php
    $nim = $_GET["nim"];
    $detailTransfer = true;
    $data_tujuan = $db->getUserByNIM($nim, PDO::FETCH_OBJ);

    $diri_sendiri = $nim == $data["nim"];
    $beda_jurusan = $data_tujuan? $data_tujuan->id_jurusan != $data["id_jurusan"] : null; 

    // $detailTransfer = $db->transfer($data["nim"], $nim);

    if($data_tujuan && !$diri_sendiri && !$beda_jurusan) {
    ?>
        <h1 class="display-6 mb-0">Transfer ke <?=explode(" ", $data_tujuan->nama)[0]?></h1>
        <p class="lead text-muted pt-0"><?= $nim ?></p>

        <form action="../aksi/transfer_saldo.php" method="post" class="mt-3">
            <div class="form-group">
                <label for="nominal_transfer">Nominal Transfer</label><br>
              
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="number" class="form-control uang" name="nominal" min="500" max="<?=$data["saldo"]?>" value="10000" id="nominal_transfer" required>
                </div>
           
            </div>

            <input type="hidden" name="mahasiswaid" value="<?= $data["id"] ?>">
            <input type="hidden" name="nim" value="<?= $nim ?>">
            <input type="hidden" name="metode" value="<?=isset($_GET["metode"]) ? $_GET["metode"] : "qrcode"?>">

            <input type="submit" class="btn btn-primary btn-lg" value="Transfer">
        </form>
        
    <?php
    } else {
    ?>

    <h1>Gagal!</h1>
    <span><?=$diri_sendiri ? "Tidak dapat mentransfer ke akun sendiri": ($beda_jurusan ? "Tidak dapat mentransfer ke jurusan lain": "NIM tidak ditemukan")?></span>
    <br/>

    <?php 
        $satusState = false;
        $iconNo = "siswa-times";
        include "../component/statusIcon.php";
    ?>

    <button onclick="history.back()" role="button" class="btn btn-primary btn-lg mt-2">Kembali</button>

    <?php } ?>

    </div>

    <?php include "../component/mahasiswa/sidebarclose.php" ?>
    <?php include "../component/scripts.php" ?>
    <?php require "../component/scrollTop.php" ?>
</body>

</html> 