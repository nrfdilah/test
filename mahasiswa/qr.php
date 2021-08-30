<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>
    <title>Kode QR</title>
</head>

<body>
    <?php include "../process/getLoginData.php" ?>
    <?php include "../component/mahasiswa/sidebaropen.php" ?>

    <?php
    include "../phpqrcode/qrlib.php";

    $file = "../qrcodes/" . $data["nim"] . ".png";

    if(!file_exists($file)) {
        QRCode::png($data["nim"], $file, "L", 10.5, 1);
    }
    ?>

    <div class="card">
        <div class="card-header">
            Scan QR ini untuk transfer
        </div>
        <div class="card-body text-center">
            <img src="../qrcodes/<?= $data["nim"] ?>.png"><br/>
            <small><?= $data["nim"] ?></small>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary w-100" onclick="history.back()">Kembali</button>
        </div>
    </div>


    <?php include "../component/mahasiswa/sidebarclose.php" ?>
    <?php include "../component/scripts.php" ?>
</body>

</html>