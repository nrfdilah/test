<?php
require "checkpost.php";
require "../db/database.php";

$validated = false;

if (isset($_POST["mahasiswaid"])) {
    $db = new Database();

    $mahasiswaid = $_POST["mahasiswaid"];
    $adminid = $_POST["adminid"];
    $nominal = (int)$_POST["nominal_tarik"];
    $deskripsi = $_POST["deskripsi"];
    $pass = $_POST["password"];

    $validated = $db->validateAdminPassword($adminid, $pass);

    if ($validated && $nominal >= 1000 && $nominal <= $db->getUserById($mahasiswaid, PDO::FETCH_OBJ)->saldo) {
        if ($db->mahasiswaWithdrawal($mahasiswaid, $nominal)) {
            $db->addTransaction($nominal, "tarik", "keluar", $mahasiswaid, "teller", $deskripsi);
            $db->addAdminJournal($adminid, "tarik_tunai_mahasiswa", $nominal, $mahasiswaid);
            
            header("Location: ../admin/detail_mahasiswa.php?ssc=Tarik Tunai Sukses&id=$mahasiswaid");
            die();
        }
    } else {
        header("Location: ../admin/detail_mahasiswa.php?ssc=Password salah atau nominal tarik terlalu kecil&id=$mahasiswaid");        
    }
}
?>
