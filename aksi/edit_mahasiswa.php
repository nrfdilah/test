<?php
require "checkpost.php";
require "../db/database.php";

$db = new Database();

$pass = $db->editUserFull(
    $_POST["id"],
    $_POST["nama"],
    $_POST["idjurusan"],
    $_POST["jenis_kelamin"],
    $_POST["email"],
    $_POST["jenjang"],
    $_POST["kelas"],
    $_POST["prodi"],
    $_POST["nim"]
);

$db->addAdminJournal($_POST["adminid"], "edit_mahasiswa", 0, $_POST["id"]);

header("Location: ../admin/detail_mahasiswa.php?id=".$_POST["id"]);
die();
