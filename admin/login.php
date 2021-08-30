<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>
    <title>Login Admin</title>
</head>

<body>
    <?php include "../process/redirectLoggedUser.php" ?>
    
    <center>
        <div class="container">
            <h1>Masuk Admin</h1>

            <div class="login">
                <form action="../aksi/login_admin.php" method="POST">
                    <div class="form-group">
                        <label for="mahasiswaemail">Email</label>
                        <input type="email" name="mahasiswaemail" class="form-control" id="mahasiswaemail" placeholder="Email">

                    </div>
                    <div class="form-group">
                        <label for="mahasiswapass">Kata Sandi</label>
                        <input type="password" name="mahasiswapass" class="form-control" id="mahasiswapass" placeholder="Kata Sandi">
                    </div>

                    <!-- <div class="right-left"> -->
                    <!-- <div class="form-check">
                            <input type="checkbox" name="rememberme" class="form-check-input" id="rememberme">
                            <label class="form-check-label" for="rememberme">Ingat Saya</label>
                        </div> -->

                    <div class="right-left">
                        <div>
                            <a href="../" class="btn btn-primary">Kembali</a>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-success">Masuk</button>                                
                        </div>
                    </div>
                    <!-- </div> -->
                </form>
            </div>
        </div>
    </center>
    
</body>

</html> 