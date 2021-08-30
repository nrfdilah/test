<nav class="navbar hide-small fixed-top navbar-expand-sm bg-primary navbar-dark navbar-fixed">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
        <li class="nav-item active">
        </li>
    </ul>
    <a class="navbar-brand" href="home.php"><img src="../assets/dasi_white.svg" alt="">Dasi</a>
</nav> 

<div class="page-wrapper toggled chiller-theme">
    <a id="show-sidebar" class="btn btn-sm text-white" href="#">
        <i class="fas fa-bars"></i>
    </a>
    <nav id="sidebar" class="sidebar-wrapper">
        <div class="sidebar-content">
            <div class="sidebar-brand" id="close-sidebar">
                <a>Tutup</a>
                <div>
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <div class="sidebar-header">
                <div class="mahasiswa-info">
                    <span class="mahasiswa-name"><?=ucwords($data["nama"])?></span>
                </div>
            </div>
            
            <!-- sidebar-search  -->
            <div class="sidebar-menu">
                <ul>
                    <li class="header-menu">
                        <span>Menu</span>
                    </li>
                    <li>
                        <a href="home.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Home</span>
                        </a>
                    </li>

                    <li>
                        <a href="mahasiswa.php">
                            <i class="fas fa-user"></i>
                            <span>Mahasiswa</span>
                        </a>
                    </li>

                    <li>
                        <a href="kantin.php">
                            <i class="fas fa-store"></i>
                            <span>Kantin</span>
                        </a>
                    </li>

                    <li>
                        <a href="donasi.php">
                            <i class="fas fa-hand-holding-usd"></i>
                            <span>Donasi</span>
                        </a>
                    </li>

                    <li>
                        <a href="ukt.php">
                            <i class="fas fa-book"></i>
                            <span>UKT</span>
                        </a>
                    </li>

                    <li>
                        <a href="transaksi.php">
                            <i class="fas fa-history"></i>
                            <span>Transaksi Mahasiswa</span>
                        </a>
                    </li>

                    <li>
                        <a href="aktivitas.php">
                            <i class="fas fa-journal-whills"></i>
                            <span>Aktivitas Admin</span>
                        </a>
                    </li>
                     
                </ul>
            </div>
            <!-- sidebar-menu  -->
        </div>
        <!-- sidebar-content  -->
        <div class="sidebar-footer">
            <a href="pengaturan.php">
                <i class="fa fa-cog"></i>
            </a>
            <a href="../aksi/logout.php">
                <i class="fa fa-power-off"></i>
            </a>
        </div>
    </nav>
    <!-- sidebar-wrapper  -->
    <main class="page-content"> 
    <div class="container-fluid" id="mainbody">