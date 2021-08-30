<?php
require "checkpost.php";
require "../db/database.php";

$db = new Database();

$res = $db->register(
    $_POST["nama"],
    $_POST["idjurusan"],
    $_POST["jenis_kelamin"],
    $_POST["email"],
    $_POST["jenjang"],
    $_POST["kelas"],
    $_POST["prodi"],
    $_POST["nim"],
    $_POST["saldo"]
);

$db->addAdminJournal($_POST["adminid"], "regist_mahasiswa", 0, $res[0]);

echo "Password: $res[1]<br/>Harap Diingat";
