<?php 
session_start();
	
	date_default_timezone_set("Asia/Jakarta");

	$host		= "localhost";
	$username	= "root";
	$password	= "";
	$database	= "popay";

	$conn 		= mysqli_connect($host, $username, $password, $database);
	if ($conn) {
		// echo "berhasil terkoneksi!";
	} else {
		echo "gagal terkoneksi!";
	}

    function deleteMahasiswa($id) {
        global $conn;
            $query = mysqli_query($conn, "DELETE * FROM mahasiswa WHERE id_jurusan = '$id'");
              return mysqli_affected_rows($conn);      
    }