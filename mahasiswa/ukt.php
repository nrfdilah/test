<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>
    <title>UKT</title>
</head>

<body>
    <?php include "../process/getLoginData.php" ?>
    <?php include "../component/mahasiswa/sidebaropen.php" ?>

    <div class="modal fade" id="confirmmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Konfirmasi Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <form class="m-2" method="post" action="../aksi/bayar_ukt.php">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>

                    <input type="hidden" name="idmahasiswa" value="<?=$data["id"]?>">
                    <input type="hidden" name="idjurusan" value="<?=$data["id_jurusan"]?>">
                    <input type="hidden" name="idukt" id="idukt" value="">
                    <input type="hidden" name="bulanukt" id="bulanukt" value="">

                    <input type="submit" class="btn btn-primary" value="Konfirmasi">
                </form>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Batal</button>
            </div>
            </div>
        </div>
    </div>

    <?php
        $res = $db->getUserUKT($data["id"], PDO::FETCH_OBJ);
        $chunk = array_chunk($res, ceil(count($res) / 2));

        $tagihanUKT = array_merge($chunk[1], $chunk[0]);

        $jumlahLunas = 0;
        $bulanIniLunas = false;

        foreach($tagihanUKT as $ukt) {
            if($ukt->status_pembayaran) $jumlahLunas++;
            if($ukt->bulan == getBulan() && $ukt->status_pembayaran) $bulanIniLunas = true;
        }

        $lunas = $jumlahLunas >= 12;
    ?>

    <div class="card <?=$lunas || $bulanIniLunas? "bg-success" : "bg-danger"?>">
        <div class="card-body  text-center ">
            <span class="text-white lead">
                <?php
                if ($lunas) {
                    echo 'Selamat, UKT anda sudah lunas!';
                } else if($bulanIniLunas) {
                    echo 'UKT kamu bulan ini sudah lunas!';
                } else if(!$bulanIniLunas) {
                    echo 'Silahkan melunasi UKT bulan ini';
                } else {
                    echo 'Silahkan melunasi UKT yang belum dilunasi';
                }
                ?>
            </span>
        </div>
    </div>

    <br>

    <div class="card">
        <div class="card-body">
            <div class="row">

            <?php
                foreach($tagihanUKT as $ukt) {
                $tenggang = bulanJurusanToNum($ukt->bulan) < bulanJurusanToNum(getBulan());
            ?>
                <div class="col-sm-4 my-3">
                    <div class="card" id="<?=$ukt->bulan?>">
                        <div class="card-body <?=$ukt->bulan == getBulan()? "pb-0" :"pb-4"?>">
                            <h3 class="card-title">
                                <?=ucwords($ukt->bulan)?>
                            </h3>

                            <?php if($ukt->status_pembayaran) { ?>
                                <p>Yeay! UKT bulan ini sudah kamu bayar pada tanggal</p>
                                <p><?=indonesian_date($ukt->tanggal_pembayaran), date('H:i:s', strtotime($ukt->tanggal_pembayaran))?></p>
                            <?php } else { ?>
                                <p>Kamu belum membayar UKT bulan ini, yuk segera bayar</p>

                                <div class="right-left">
                                <div>
                                    <h5 class="card-text pt-2 font-weight-bold float-sm-left mb-0">
                                        <?=boldGreen(rupiah($jurusan->biaya_ukt))?>
                                    </h5>
                                </div>

                                <div>
                                    <button 
                                        class="btn btn-primary mb-2 <?=bulanJurusanToNum($ukt->bulan) < bulanJurusanToNum(getBulan()) ? ($ukt->status_pembayaran ? "btn-success" : "btn-danger")." text-white": "btn-primary" ?>" 
                                        type="button" 
                                        id="btn-<?=$ukt->bulan?>"
                                        data-toggle="modal" 
                                        data-target="#confirmmodal"
                                        data-idukt="<?=$ukt->id?>"
                                        data-bulanukt="<?=$ukt->bulan?>"
                                    >
                                        Bayar
                                    </button>
                                </div>
                            </div>

                            <?php } ?>

                            
                        </div>
                        <?php if($ukt->bulan == getBulan()) { ?>
                            <div class="card-footer bg-primary"></div>        
                        <?php } ?>
                    </div>
                </div>
            <?php
                }
            ?>

            </div>
        </div>
    </div>

    <?php include "../component/mahasiswa/sidebarclose.php" ?>
    <?php include "../component/scripts.php" ?>
    <?php require "../component/scrollTop.php" ?>

    <script>
         $(document).ready(function () {
            $('#confirmmodal').on('shown.bs.modal', function(e) {
                $('#idukt').val($(e.relatedTarget).data("idukt"));
                $('#bulanukt').val($(e.relatedTarget).data("bulanukt"));
            });

            <?php if(isset($_GET["focus"])) { ?>
                setTimeout(() => {
                    $('html, body').animate(
                        {
                            scrollTop: $('#<?=$_GET["focus"]?>').offset().top - 200
                        },
                        1000
                    );

                    $("#btn-<?=$_GET["focus"]?>").focus();
                }, 400)
            <?php } ?>
        });
    </script>
</body>

</html> 
