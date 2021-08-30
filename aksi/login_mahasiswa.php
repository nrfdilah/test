<?php
require "checkpost.php";
session_start();

require "../db/database.php";

$db = new Database();

$res = $db->login($_POST["mahasiswaemail"], $_POST["mahasiswapass"], "*");

if($res) {
    $_SESSION['mahasiswaid'] = $res;
    $_SESSION['level'] = "mahasiswa";

    header("Location: ../mahasiswa/home.php");
    die();
} else {
    header("Location: ../mahasiswa/login.php?error=1");
    die();
}
