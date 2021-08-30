<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>
    <title>UKT Mahasiswa</title>
</head>

<body>
    <?php include "../process/getAdminLoginData.php" ?>
    <?php include "../component/admin/sidebaropen.php" ?>

    <?php
        $trx = $db->getJurusanUKTTransactions($data["id_jurusan"]);
        $jurusan = $db->getJurusanData($data["id_jurusan"], PDO::FETCH_OBJ);

        $idx = 0;
    ?>

    <h1>UKT Mahasiswa</h1>

    <div class="card">
        <div class="card-body">
            <h3 class="card-title text-success"><?=rupiah($jurusan->saldo)?></h3>

            <form action="../aksi/tarik_tunai_ukt.php" method="post">

                <div class="form-group">
                    <label for="jumlah_penarikan">Jumlah Penarikan</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="number" class="form-control uang" name="nominal" id="jumlah_penarikan" min="1" max="<?=$jurusan->saldo?>" value="1000000" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password Admin</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <input type="hidden" name="adminid" value="<?= $data["id"] ?>">
                <input type="hidden" name="id_jurusan" value="<?= $data["id_jurusan"] ?>">

                <input type="submit" class="btn btn-primary" value="Tarik">
            </form>

            <form action="../aksi/ubah_biaya_ukt.php" class="mt-4" method="post">

                <div class="form-group">
                    <label for="jumlah_penarikan">Biaya UKT</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input type="number" class="form-control uang" name="biaya_ukt" id="jumlah_penarikan" min="1" value="<?=$jurusan->biaya_ukt?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password Admin</label>
                    <input type="password" class="form-control" name="password" required>
                </div>

                <input type="hidden" name="adminid" value="<?= $data["id"] ?>">
                <input type="hidden" name="id_jurusan" value="<?= $data["id_jurusan"] ?>">

                <input type="submit" class="btn btn-primary" value="Ubah">
            </form>
        </div>
    </div>

    <?php if ($trx) { ?>
        <div class="row">
            <?php foreach($trx as $t){ ?>
                <div class="col-sm-4 col-l-3 my-3">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title pointer text-success" onclick="location.href = 'detail_mahasiswa.php?id=<?=$t->mahasiswa_id?>'">
                                <?=$t->mahasiswa_id?>
                                <span class="float-right"><?=boldGreen(rupiah($t->kredit))?></span>
                            </h3>

                            <p class="card-text"><?=$t->deskripsi?></p>

                            <p class="card-text"><?=indonesian_date($t->tanggal), date('H:i:s', strtotime($t->tanggal))?></p>
                        </div>
                    </div>
                </div>
            <?php if($idx++ >= 11) break; } ?>
        </div>        
    <?php } else { 
        echo "<p>Belum terdapat transaksi</p>";
    } ?>

    <?php include "../component/mahasiswa/sidebarclose.php" ?>
    <?php include "../component/scripts.php" ?>
    <?php $noback = true; require "../component/scrollTop.php" ?>
</body>

</html> 