<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>
    <title>History</title>
</head>

<body>
    <?php include "../process/getAdminLoginData.php" ?>
    <?php include "../component/admin/sidebaropen.php" ?>

    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Mahasiswa
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#tambah_mahasiswa">Tambah Mahasiswa</button>
            </h1>

            <div class="collapse mb-4" id="tambah_mahasiswa">
                <form action="../aksi/mahasiswa_baru.php" method="post">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="nama_mahasiswa">Nama Mahasiswa</label>
                                <input type="text" class="form-control" name="nama" id="nama_mahasiswa" required>
                            </div>

                            <div class="form-group">
                                <label for="jenis_kelamin">Jenis Kelamin Mahasiswa</label>
                                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="laki-laki">Laki-Laki</option>
                                    <option value="perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="email_mahasiswa">Email Mahasiswa</label>
                                <input type="email" class="form-control" name="email" id="email_mahasiswa" required>
                            </div>

                            <div class="form-group">
                                <label for="jenjang">Jenjang Mahasiswa</label>
                                <select class="form-control" id="jenjang" name="jenjang" required>
                                    <!-- <option value="I">I</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="IV">IV</option>
                                    <option value="V">V</option>
                                    <option value="VI">VI</option> -->
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="kelas_mahasiswa">Kelas Mahasiswa</label>
                                <input type="text" class="form-control" name="kelas" id="kelas_mahasiswa" required>
                            </div>

                            <div class="form-group">
                                <label for="prodi_mahasiswa">Program Studi Mahasiswa</label>
                                <input type="text" class="form-control" name="prodi" id="prodi_mahasiswa" required>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="nim_mahasiswa">NIM Mahasiswa</label>
                                <input type="text" class="form-control" name="nim" id="nim_mahasiswa" required>
                            </div>

                            <div class="form-group">
                                <label for="saldo_awal_mahasiswa">Saldo Awal Mahasiswa</label>
                                <input type="number" class="form-control uang" name="saldo" id="saldo_awal_mahasiswa" value="0" required>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="idjurusan" value="<?=$data["id_jurusan"]?>">
                    <input type="hidden" name="adminid" value="<?=$data["id"]?>">

                    <input type="submit" class="btn btn-primary" value="Masukan">
                </form>
            </div>

            <div class="card card-body">
                <?php 
                $mahasiswa = $db->getSeluruhMahasiswa($data["id_jurusan"]);
                if ($mahasiswa) {

                    ?>

                <div class="table-responsive">
                    <table id="listMahasiswa" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal Pendaftaran</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Email</th>
                                <th>Kelas</th>
                                <th>NIM</th>
                                <th>Saldo</th>
                                <th></th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($mahasiswa as $mahasiswa) {
                                ?>

                            <tr>
                                <td><?= $mahasiswa->id ?></td>
                                <td><?= $mahasiswa->tanggal_pendaftaran ?></td>
                                <td><?= ucwords($mahasiswa->nama) ?></td>
                                <td><?= $mahasiswa->jenis_kelamin ?></td>
                                <td><?= $mahasiswa->email ?></td>
                                <td><?= "$mahasiswa->jenjang $mahasiswa->prodi $mahasiswa->kelas" ?></td>
                                <td><?= $mahasiswa->nim ?></td>
                                <td data-sort="<?=$mahasiswa->saldo?>"><?= rupiah($mahasiswa->saldo) ?></td>
                                <td><a href="detail_mahasiswa.php?id=<?=$mahasiswa->id?>" class="btn btn-primary">Detail</a></td>
                                <td><a href="hapus.php?id=<?php echo $mahasiswa->id?>" class="btn btn-primary">Hapus</a></td>
                            </tr>

                            <?php

                        }
                        ?>

                        </tbody>
                    </table>
                </div>

                <?php

            } else {
                echo "<p class='card-text'>Tidak ada mahasiswa yang dapat ditampilkan</p>";
            }
            ?>
            </div>
        </div>

    </div>

    <?php include "../component/mahasiswa/sidebarclose.php" ?>
    <?php include "../component/scripts.php" ?>
    <?php require "../component/scrollTop.php" ?>

    <script>
        $(document).ready(function() {
            $('#listMahasiswa').DataTable({
                "order": [
                    [1, "desc"]
                ],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ mahasiswa per halaman",
                    "zeroRecords": "Maaf, tidak dapat menemukan apapun",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_ halaman",
                    "infoEmpty": "Tidak ada mahasiswa yang dapat ditampilkan",
                    "infoFiltered": "(tersaring dari _MAX_ total mahasiswa)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    },
                }
            });
        });
    </script>
</body>

</html> 