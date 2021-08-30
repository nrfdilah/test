<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>
    <title>Home</title>
</head>

<body>
    <?php include "../process/getAdminLoginData.php" ?>
    <?php include "../component/admin/sidebaropen.php" ?>

    <?php
    $jurusan = $db->getJurusanData($data["id_jurusan"], PDO::FETCH_OBJ);

    $stats = $db->getJurusanStats($data["id_jurusan"]);

    // print_r($trx);
    ?>

    <h1>Home</h1>


    <div class="card my-4">
        <div class="card-body">
            <h3 class="card-title"><?= $jurusan->nama_jurusan ?> <span class="text-muted lead"><?= $jurusan->telepon ?></span></h3>
            <?= $jurusan->kode ? "<p>Kode Jurusan Pendaftaran Mahasiswa: $jurusan->kode</p>" : "" ?>

            <div class="row">
                <div class="col-sm-4 my-2">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-male" aria-hidden="true"></i> Laki-laki</h4>
                            <p class="card-text"><?= $stats->mahasiswa->laki ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 my-2">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-female"></i> Perempuan</h4>
                            <p class="card-text"><?= $stats->mahasiswa->perempuan ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 my-2">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-male"></i><i class="fas fa-female"></i> Total</h4>
                            <p class="card-text"><?= $stats->mahasiswa->total ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3 mt-2">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-school" aria-hidden="true"></i> Jurusan</h4>
                            <p class="card-text"><?= boldGreen(rupiah($stats->balance->jurusan)) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 mt-2">
                    <div class="card pointer" onclick="location.href = 'mahasiswa.php'">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-user"></i> Mahasiswa</h4>
                            <p class="card-text"><?= boldGreen(rupiah($stats->balance->mahasiswa)) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 mt-2">
                    <div class="card pointer" onclick="location.href = 'kantin.php'">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-store"></i> Kantin</h4>
                            <p class="card-text"><?= boldGreen(rupiah($stats->balance->kantin)) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 mt-2">
                    <div class="card pointer" onclick="location.href = 'donasi.php'">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-hand-holding-usd"></i> Dansos</h4>
                            <p class="card-text"><?= boldGreen(rupiah($stats->balance->donasi)) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="mt-4">Total: <?= boldGreen(rupiah($stats->balance->total)) ?></h4>
        </div>
    </div>

    <?php include "../component/admin/sidebarclose.php" ?>
    <?php include "../component/scripts.php" ?>
    <?php $noback = true; require "../component/scrollTop.php" ?>
</body>

</html>