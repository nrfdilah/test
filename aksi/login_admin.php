<?php
require "checkpost.php";
session_start();

require "../db/database.php";

$db = new Database();

$res = $db->loginAdmin($_POST["mahasiswaemail"], $_POST["mahasiswapass"], "*");

if($res) {
    $_SESSION['adminid'] = $res;
    $_SESSION['level'] = "admin";

    $db->addAdminJournal($res, "login", 0);

    header("Location: ../admin/home.php");
    die();
} else {
    header("Location: ../admin/login.php?error=1");
    die();
}
