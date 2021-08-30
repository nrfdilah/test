<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'component/things.php' ?>

    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="stylesheet" href="styles/landing.css">

    <title>PoPay</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="manifest" href="/manifest.json">
          <link rel="apple-touch-icon" href="assets/images/icon-152.png">
  <meta name="theme-color" content="white"/>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="Popay app">
  <meta name="msapplication-TileImage" content="assets/images/icon-144.png">
  <meta name="msapplication-TileColor" content="#FFFFFF">
</head>

<body id="blog">
    <?php
    session_start();

    require 'db/database.php';

    if (isset($_SESSION['mahasiswaid'])) {
        header("Location: mahasiswa/home.php");
        die();
    } else if (isset($_SESSION['adminid'])) {
        header("Location: admin/home.php");
        die();
    }

    ?>


    <nav class="navbar fixed-top white navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#"> <img src="assets/logo_colored.svg" width="30" height="30" class="d-inline-block align-top" alt=""></a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Login
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="mahasiswa/login.php">Mahasiswa</a>
                        <a class="dropdown-item" href="admin/login.php">Jurusan</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link pointer" id="daftar-jurusan">Daftar Jurusan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pointer" id="daftar-mahasiswa">Daftar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Tentang</a>
                </li>
            </ul>
        </div>
    </nav>


    <header class="masthead">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 text-left brand-container">
                    <h1 class="font-weight-light">Dasi</h1>
                    <p class="lead">Dana Mahasiswa</p>
                </div>
            </div>
        </div>
    </header>


    <section class="py-5">
        <div class="container">
            <div class="container text-center py-2">
                <h2 class="font-weight-light">Apa itu Dasi?</h2>
                <p>Dasi adalah sebuah dompet elektronik yang dikhususkan di lingkungan
                    jurusan. Mahasiswa dapat menabung, membayar di kantin, UKT, Dansos, dan lainya dengan
                    mudah dan cepat. Orang tua juga dapat mengawasi riwayat transaksi mahasiswa.</p>
            </div>

            <div class="container text-center py-2">
                <h2 class="font-weight-light">Fitur Utama Dasi</h2>
                <div class="row">
                    <div class="col-sm-4 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-qrcode" aria-hidden="true"></i> Kode QR</h5>
                                <p class="card-text">Bayar di kantin atau transfer saldo dengan Kode QR yang tersedia</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 pt-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-hand-holding-usd"></i> Donasi</h5>
                                <p class="card-text">Mahasiswa dapat berdonasi dengan cepat dan mudah menggunakan dasi</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 py-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-file-invoice"></i> UKT</h5>
                                <p class="card-text">Bayar UKT dengan cepat dan mudah tanpa antri tanpa harus pergi ke TU</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container py-2 text-center form-daftar-jurusan">
                <h2 class="font-weight-light">Pendaftaran Jurusan</h2>

                <p>Daftarkan jurusan anda dan nikmati kemudahanya!</p>

                <form action="aksi/daftar_jurusan.php" method="post" class="mt-4">
                    <div class="form-group">
                        <label for="nama_jurusan">Nama Jurusan</label>
                        <input type="text" name="nama_jurusan" id="nama_jurusan" class="form-control input-sm" required>
                    </div>

                    <div class="form-group">
                        <label for="email_jurusan">Email Jurusan</label>
                        <input type="email" name="email_jurusan" id="email_jurusan" class="form-control input-sm" required>
                    </div>

                    <div class="form-group">
                        <label for="telepon_jurusan">No. Telepon</label>
                        <input type="number" name="telepon_jurusan" id="telepon_jurusan" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Bidang Studi</label>
                        <select class="form-control" id="bidang_studi" name="bidang_studi" required>
                            <option value="Bidang Studi Rekayasa/Engineering">Bidang Studi Rekayasa/Engineering</option>
                            <option value="Bidang Studi Tata Niaga">Bidang Studi Tata Niaga</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="program_studi">Program Studi</label>
                        <select class="form-control" id="program_studi" name="program_studi" required>
                            <option value="Teknik Komputer & Jaringan">Teknik Komputer & Jaringan</option>
                            <option value="Teknik Telekomunikasi">Teknik Telekomunikasi</option>
                            <option value="Teknik Elektronika">Teknik Elektronika</option>
                            <option value="Teknik Listrik">Teknik Listrik</option>
                            <option value="Teknik Multimedia dan Jaringan">Teknik Multimedia dan Jaringan</option>
                            <option value="Teknik Pembangkit Energi">Teknik Pembangkit Energi</option>
                            <option value="Teknik Manufaktur">Teknik Manufaktur</option>
                            <option value="Teknik Mekatronika">Teknik Mekatronika</option>
                            <option value="Teknik Konversi Energi">Teknik Konversi Energi</option>
                            <option value="Teknik Mesin">Teknik Mesin</option>
                            <option value="Teknik Otomotif">Teknik Otomotif</option>
                            <option value="Teknik Perawatan Alat Berat">Teknik Perawatan Alat Berat</option>
                            <option value="Teknik Konstruksi Gedung">Teknik  Konstruksi Gedung</option>
                            <option value="Teknik Konstruksi Sipil">Teknik Konstruksi Sipil</option>
                            <option value="Teknik Perancangan Bangunan Gedung">Teknik Perancangan Bangunan Gedung</option>
                            <option value="Teknik Jasa Konstruksi">Teknik Jasa Konstruksi</option>
                            <option value="Teknik Kimia">Teknik Kimia</option>
                            <option value="Teknik Analisis Kimia">Teknik Analisis Kimia</option>
                            <option value="Teknik Kimia Industri">Teknik Kimia Industri</option>
                            <option value="Administrasi Bisnis">Administrasi Bisnis</option>
                            <option value="Akuntansi">Akuntansi</option>
                            <option value="Akuntansi Manajerial">Akuntansi Manajerial</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="kode" name="kode">
                            <label class="form-check-label" for="kode">Perbolehkan mahasiswa Mendaftar Sendiri</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Daftarkan</button>
                </form>
            </div>

            <div class="container py-2 text-center form-daftar-mahasiswa">
                <h2 class="font-weight-light">Pendaftaran Mahasiswa</h2>

                <p>Jurusan kamu menggunakan dasi? kamu bisa mendaftar dengan form di bawah ini!</p>

                <form action="aksi/regist_mahasiswa.php" method="post">
                    <div>
                        <div class="form-group">
                            <label for="kode_jurusan">Kode Jurusan</label>
                            <input type="text" class="form-control" name="kode_jurusan" id="kode_jurusan" required>
                        </div>

                        <div class="form-group">
                            <label for="nama_mahasiswa">Nama</label>
                            <input type="text" class="form-control" name="nama" id="nama_mahasiswa" required>
                        </div>

                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="laki-laki">Laki-Laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label for="email_mahasiswa">Email</label>
                            <input type="email" class="form-control" name="email" id="email_mahasiswa" required>
                        </div>

                        <div class="form-group">
                            <label for="jenjang">Jenjang</label>
                            <select class="form-control" id="jenjang" name="jenjang" required>
                                <option value="D3" selected>D3</option>
                                <option value="D4">D4</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label for="prodi_mahasiswa">Program Studi</label>
                            <input type="text" class="form-control" name="prodi" id="prodi_mahasiswa" required>
                        </div>

                        <div class="form-group">
                            <label for="kelas_mahasiswa">Kelas</label>
                            <input type="text" class="form-control" name="kelas" id="kelas_mahasiswa" required>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label for="nim_mahasiswa">NIM</label>
                            <input type="number" class="form-control" name="nim" id="nim_mahasiswa" required>
                        </div>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Masukan">
                </form>
            </div>
        </div>
    </section>


    <footer class="page-footer font-small blue pt-4">
        <div class="container-fluid text-center">
            <h5 class="text-uppercase">PoPay</h5>
        </div>

        <div class="footer-copyright text-center py-3">© 2021
            <a href="https://www.poliupg.ac.id/"> Politeknik Negeri Ujung Pandang</a>
        </div>

    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <?php $noback = true; require 'component/scrollTop.php'; ?>

    <script>
        $("#daftar-jurusan").on("click", () => {
            $('html, body').animate({
                    scrollTop: $('.form-daftar-jurusan').offset().top - 60
                },
                1000
            );
        })

        $("#daftar-mahasiswa").on("click", () => {
            $('html, body').animate({
                    scrollTop: $('.form-daftar-mahasiswa').offset().top - 60
                },
                1000
            );
        })
    </script>
    <script src="assets/js/main.js"></script>
</body>

</html>