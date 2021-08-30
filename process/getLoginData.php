<?php
session_start();

require '../db/database.php';

if (!isset($_SESSION['mahasiswaid'])) {
    header("Location: ../mahasiswa/login.php");
    die();
}

$db = new Database();
$data = $db->getUserById($_SESSION['mahasiswaid'], PDO::FETCH_ASSOC);

if ($_SESSION['level'] != "mahasiswa") {
    header("Location: ../admin/home.php");
    die();
}
