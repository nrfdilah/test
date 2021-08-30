<?php
require "checkpost.php";
require "../db/database.php";

$db = new Database();

$success = false;

$validated = $db->validateAdminPassword($_POST["adminid"], $_POST["password"]);

if ($validated) {
    if ($db->changeJurusanBiayaUKT(
        $_POST["id_jurusan"],
        $_POST["biaya_ukt"]
    )) {
        $success = true;
        header("Location: ../admin/ukt.php?scc=Berhasil mengubah biaya ukt");
        die();
    }

    header("Location: ../admin/ukt.php?scc=Terjadi kesalahan");
    die();
} else {
    header("Location: ../admin/ukt.php?scc=Password Salah");
    die();
}
