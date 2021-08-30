<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>
    <title>Mahasiswa Baru</title>
</head>

<body>
    <?php include "../process/getAdminLoginData.php" ?>
    <?php include "../component/admin/sidebaropen.php" ?>

    <h1>Form Akun Mahasiswa Baru</h1>

    <form action="../aksi/mahasiswa_baru.php" method="post">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="nama_mahasiswa">Nama Mahasiswa</label>
                    <input type="text" class="form-control" name="nama" id="nama_mahasiswa" placeholder="Hafizh Beckham" required>
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
                    <input type="email" class="form-control" name="email" id="email_mahasiswa" placeholder="hafizh@beckham.me" required>
                </div>

                <div class="form-group">
                    <label for="jenjang">Jenjang Mahasiswa</label>
                    <select class="form-control" id="jenjang" name="jenjang" required>
                    <option value="D3" selected>D3</option>
                    <option value="D4">D4</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="kelas_mahasiswa">Kelas Mahasiswa</label>
                    <input type="text" class="form-control" name="kelas" id="kelas_mahasiswa" placeholder="A/B/C" required>
                </div>

                <div class="form-group">
                    <label for="prodi_mahasiswa">Program Studi Mahasiswa</label>
                    <input type="text" class="form-control" name="prodi" id="prodi_mahasiswa" placeholder="RPL" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="nim_mahasiswa">NIM Mahasiswa</label>
                    <input type="text" class="form-control" name="nim" id="nim_mahasiswa" placeholder="1234567890" required>
                </div>

                <div class="form-group">
                    <label for="saldo_awal_mahasiswa">Saldo Awal Mahasiswa</label>
                    <input type="number" class="form-control uang" name="saldo" id="saldo_awal_mahasiswa" value="0" required>
                </div>
            </div>
        </div>

        <input type="hidden" name="idjurusan" value="<?=$data["id_jurusan"]?>">

        <input type="submit" class="btn btn-primary" value="Masukan">
    </form>

    </div>



    <?php include "../component/admin/sidebarclose.php" ?>
    <?php include "../component/scripts.php" ?>
</body>

</html> 