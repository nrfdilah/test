<?php
session_start();

require __DIR__ . '/../db/database.php';

if (isset($_SESSION['mahasiswaid'])) {
    header("Location: ../mahasiswa/home.php");
    die();
} else if (isset($_SESSION['adminid'])) {
    header("Location: ../admin/home.php");
    die();
}
