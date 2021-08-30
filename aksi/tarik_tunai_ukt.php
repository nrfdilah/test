<?php
require "checkpost.php";
require "../db/database.php";

$validated = false;

if (isset($_POST["id_jurusan"])) {
    $db = new Database();

    $id_jurusan = $_POST["id_jurusan"];
    $adminid = $_POST["adminid"];
    $nominal = (int)$_POST["nominal"];
    $pass = $_POST["password"];

    $validated = $db->validateAdminPassword($adminid, $pass);
    $jurusan = $db->getJurusanData($id_jurusan, PDO::FETCH_OBJ);

    if ($validated && $nominal >= 1000 && $nominal <= $jurusan->saldo) {
        if ($db->uktWithdrawal($id_jurusan, $nominal)) {
            $db->addAdminJournal($adminid, "tarik_ukt", $nominal, $adminid);

            header("Location: ../admin/ukt.php?ssc=Tarik Tunai Sukses&id=$id_jurusan");
            die();
        }
    } else {
        $errmess = "Kesalahan tidak diketahui";

        if(!$validated) $errmess = "Terjadi kesalahan autentikasi";
        else if($nominal < 1000) $errmess = "Nominal penarikan terlalu kecil";
        else if($nominal > $jurusan->saldo) $errmess = "Nominal penarikan terlalu besar melebihi saldo kantin";

        header("Location: ../admin/ukt.php?ssc=$errmess&id=$id_jurusan");        
    }
}
?>
