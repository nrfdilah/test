<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>

    <title>Detail Mahasiswa</title>
</head>

<body>
    <?php include "../process/getAdminLoginData.php" ?>
    <?php include "../component/admin/sidebaropen.php" ?>

    <?php if (isset($_GET["ssc"])) { ?>
        <div class="modal" id="sccModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content bg-primary text-white">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <?= $_GET["ssc"] ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
} ?>

    <?php
    $mahasiswa = $db->getUserById($_GET["id"], PDO::FETCH_OBJ);
    ?>

    <div class="row">
        <div class="col-md-5">
            <h1><i class="fas fa-user"></i> <?= $mahasiswa->nama ?></h1>

            <div class="my-4">
                <h3><?= rupiah($mahasiswa->saldo) ?></h3>
            </div>

            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header pointer" data-toggle="collapse" data-target="#collapseOne" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn" type="button">
                                Data Mahasiswa
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <th>Nama</th>
                                    <td><?= $mahasiswa->nama ?></td>
                                </tr>
                                <tr>
                                    <th>NIM</th>
                                    <td><?= $mahasiswa->nim ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?= $mahasiswa->email ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td><?= $mahasiswa->jenis_kelamin ?></td>
                                </tr>
                                <tr>
                                    <th>Kelas</th>
                                    <td><?= "$mahasiswa->jenjang $mahasiswa->prodi $mahasiswa->kelas" ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header pointer" data-toggle="collapse" data-target="#collapseTwo" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn collapsed" type="button">
                                Edit Mahasiswa
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <form action="../aksi/edit_mahasiswa.php" method="post">
                                <p class="text-danger font-italic">*Hati-hati dan pastikan kembali jika mengubah data mahasiswa, salah mengubah data mahasiswa dapat menyebabkan hal yang tidak diinginkan</p>
                                <div class="row">
                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label for="nama_mahasiswa">Nama Mahasiswa</label>
                                            <input type="text" value="<?= $mahasiswa->nama ?>" class="form-control" name="nama" id="nama_mahasiswa" placeholder="Hafizh Beckham" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="jenis_kelamin">Jenis Kelamin Mahasiswa</label>
                                            <select class="form-control" id="kelamin" name="jenis_kelamin" required>
                                                <option value="laki-laki" <?= $mahasiswa->jenis_kelamin == "laki-laki" ? "selected" : "" ?>>Laki-Laki</option>
                                                <option value="perempuan" <?= $mahasiswa->jenis_kelamin == "perempuan" ? "selected" : "" ?>>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email_mahasiswa">Email Mahasiswa</label>
                                            <input type="email" value="<?= $mahasiswa->email ?>" class="form-control" name="email" id="email_mahasiswa" placeholder="hafizh@beckham.me" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="jenjang">Jenjang Mahasiswa</label>
                                            <select class="form-control" id="jenjang" name="jenjang" required>
                                                <option value="D3" <?= $mahasiswa->jenjang == "D3" ? "selected" : "" ?>>D3</option>
                                                <option value="D3" <?= $mahasiswa->jenjang == "D4" ? "selected" : "" ?>>D4</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="kelas_mahasiswa">Kelas Mahasiswa</label>
                                            <input type="text" value="<?= $mahasiswa->kelas ?>" class="form-control" name="kelas" id="kelas_mahasiswa" placeholder="A/B/C" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="prodi_mahasiswa">Program Studi Mahasiswa</label>
                                            <input type="text" value="<?= $mahasiswa->prodi ?>" class="form-control" name="prodi" id="prodi_mahasiswa" placeholder="RPL" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="nim_mahasiswa">NIM Mahasiswa</label>
                                            <input type="text" value="<?= $mahasiswa->nim ?>" class="form-control" name="nim" id="nim_mahasiswa" placeholder="1234567890" required>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="idjurusan" value="<?= $data["id_jurusan"] ?>">
                                <input type="hidden" name="id" value="<?= $mahasiswa->id ?>">
                                <input type="hidden" name="adminid" value="<?= $data["id"] ?>">

                                <input type="submit" class="btn btn-primary" value="Update">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card my-3" id="card-wrapper">
                <div class="card-body">
                    <a href="mahasiswa.php" class="btn btn-primary">
                        Daftar Mahasiswa
                    </a>

                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTransaksi">
                        Transaksi
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="modalTransaksi" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Transaksi</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <?php
                                        $trx = $db->getUserTransactionHistory($mahasiswa->id, PDO::FETCH_OBJ);

                                        if ($trx) {
                                            ?>

                                            <div class="table-responsive">
                                                <table id="paymentHistoryTable" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Tanggal</th>
                                                            <th>Jenis</th>
                                                            <th>Debit</th>
                                                            <th>Kredit</th>
                                                            <th>Tipe</th>
                                                            <th>Metode</th>
                                                            <th>Deskripsi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($trx as $val) {
                                                            ?>

                                                            <tr>
                                                                <td><?= $val->id ?></td>
                                                                <td><?= $val->tanggal ?></td>
                                                                <td><?= ucwords($val->jenis) ?></td>
                                                                <td data-sort="<?= $val->debit ?>"><?= rupiah($val->debit) ?></td>
                                                                <td data-sort="<?= $val->kredit ?>"><?= rupiah($val->kredit) ?></td>
                                                                <td><?= $val->tipe ?></td>
                                                                <td><?= $val->metode ?></td>
                                                                <td><?= ucwords($val->deskripsi) ?></td>
                                                            </tr>

                                                        <?php
                                                    }
                                                    ?>

                                                    </tbody>
                                                </table>
                                            </div>

                                        <?php
                                    } else {
                                        echo "<p class='card-text'>Belum melakukan transaksi</p>";
                                    }
                                    ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="printHistory()">Cetak</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card p-4 my-3" id="setor_tunai">
                <h3>Setor Tunai</h3>

                <form action="../aksi/setor_tunai_mahasiswa.php" method="post">
                    <div class="form-group">
                        <label for="jumlah_penyetoran">Jumlah Penyetoran</label>

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control uang" name="nominal_setor" id="jumlah_penyetoran" min="1000" value="1000" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi_penyetoran">Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi" value="Setor Tunai" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password Admin</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <input type="hidden" name="mahasiswaid" value="<?= $mahasiswa->id ?>">
                    <input type="hidden" name="adminid" value="<?= $data["id"] ?>">

                    <input type="submit" class="btn btn-primary" value="Setor">
                </form>
            </div>

            <div class="card p-4 my-3" id="tarik_tunai">
                <h3>Tarik Tunai</h3>

                <form action="../aksi/tarik_tunai_mahasiswa.php" method="post">

                    <div class="form-group">
                        <label for="jumlah_penarikan">Jumlah Penarikan</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control uang" name="nominal_tarik" id="jumlah_penarikan" min="1" max="<?= $mahasiswa->saldo ?>" value="10000" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi_penyetoran">Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi" value="Tarik Tunai" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password Admin</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <input type="hidden" name="mahasiswaid" value="<?= $mahasiswa->id ?>">
                    <input type="hidden" name="adminid" value="<?= $data["id"] ?>">

                    <input type="submit" class="btn btn-primary" value="Tarik">
                </form>
            </div>
        </div>
    </div>


    <?php include "../component/admin/sidebarclose.php" ?>
    <?php include "../component/scripts.php" ?>
    <?php $noback = true;
    require "../component/scrollTop.php" ?>

    <script>
        <?php if (isset($_GET["ssc"])) { ?>
            $(window).on('load', function() {
                $('#sccModal').modal('show');
            });
        <?php } ?>

        $(document).ready(function() {
            $('#paymentHistoryTable').DataTable({
                "order": [
                    [1, "desc"]
                ]
            });
        });

        function printHistory() {
            var centeredText = function(text, y) {
                var textWidth = pdf.getStringUnitWidth(text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
                var textOffset = (pdf.internal.pageSize.width - textWidth) / 2;
                pdf.text(textOffset, y, text);
            }

            var pdf = new jsPDF('p', 'pt', 'letter');

            pdf.setFontType("normal");

            centeredText("Riwayat Transaksi: <?= "{$mahasiswa->nama} - {$mahasiswa->jenjang} {$mahasiswa->prodi} {$mahasiswa->kelas}" ?>", 30);

            pdf.autoTable({
                html: "#paymentHistoryTable"
            });

            pdf.save('history-<?= $mahasiswa->nama ?>.pdf');
        }
    </script>
</body>

</html>