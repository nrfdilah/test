<?php
require "checkpost.php";
require "../db/database.php";

$db = new Database();

$res = $db->registerJurusan(
    $_POST["nama_jurusan"],
    $_POST["telepon_jurusan"],
    $_POST["bidang_studi"],
    $_POST["program_studi"],
    $_POST["alamat"],
    $_POST["email_jurusan"],
    isset($_POST["kode"])
);

if ($res) { ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <?php include "../component/things.php" ?>
        <title>UKT</title>
    </head>

    <body>
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h1>Pengurus Jurusan</h1>

                    <form action="regist_admin.php" method="post" class="mt-4">
                        <div class="form-group">
                            <label for="nama">Nama Pengurus</label>
                            <input type="text" name="nama" id="nama" class="form-control input-sm" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Kata Sandi</label>
                            <input type="password" name="password" id="password" class="form-control input-sm" required>
                        </div>

                        <input type="hidden" name="idjurusan" value="<?= $res ?>">

                        <input type="submit" value="Selesai" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>


        <?php include "../component/scripts.php" ?>

    </body>

    </html>

<?php
} else {
    echo "<div class='container text-center'>";
    echo "<h1>Maaf Tidak Dapat Mendaftarkan Jurusan, terjadi kesalahan</h1>";
    echo "<button onclick='history.back()' class='btn btn-primary'>Kembali</button>";
    echo "</div>";

    return;
}
?>