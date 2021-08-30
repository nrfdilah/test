<?php

require __DIR__ . "/../process/utils.php";

class Database
{
    public $contHost = 'localhost';
    public $contnama = 'popay';
    public $contUsernama = 'root';
    public $contUserPassword = '';

    public $cont  = null;

    public function __construct()
    {
        if ($this->cont == null) {

            try {
                $this->cont =  new PDO(
                    "mysql:host=" . $this->contHost .
                        ";" . "dbname=" . $this->contnama,
                    $this->contUsernama,
                    $this->contUserPassword
                );
                $this->cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die($e->getMessage());
            }
        }

        return $this->cont;
    }


    public function query($query)
    {
        try {
            $query = $this->cont->prepare($query);

            $query->execute();

            return $query;
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function registerJurusan($nama, $telepon, $bidang_studi, $program_studi, $alamat, $email, $dengan_kode = false)
    {
        try {
            $kode = "";

            if ($dengan_kode) {
                do {
                    $kode = generateRandom();

                    $chk = $this->cont->prepare("SELECT * FROM jurusan WHERE kode=:kode");
                    $chk->bindParam("kode", $kode);
                    $chk->execute();
                } while ($chk->rowCount() > 0);
            }

            $query = $this->cont->prepare(
                "INSERT INTO jurusan(telepon, bidang_studi, program_studi, nama_jurusan, email, alamat, biaya_ukt kode, saldo)
                VALUES (:telepon,:bidang_studi,:program_studi,:nama_jurusan,:email,:alamat,:kode,0,0)"
            );

            $query->bindParam("telepon", $telepon, PDO::PARAM_INT);
            $query->bindParam("bidang_studi", $bidang_studi, PDO::PARAM_STR);
            $query->bindParam("program_studi", $program_studi, PDO::PARAM_STR);
            $query->bindParam("nama_jurusan", $nama, PDO::PARAM_STR);
            $query->bindParam("email", $email, PDO::PARAM_STR);
            $query->bindParam("alamat", $alamat, PDO::PARAM_STR);
            $query->bindParam("kode", $kode, PDO::PARAM_STR);

            $query->execute();

            return $this->cont->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getJurusanByCode($code, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM jurusan WHERE kode=:code"
            );

            $query->bindParam("code", $code, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetch($rettype);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getJurusanData($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM jurusan WHERE id=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetch($rettype);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getJurusanTotalBalance($id)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT saldo FROM jurusan WHERE id=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $jurusanBalance = $query->fetch(PDO::FETCH_OBJ)->saldo;

            $query = $this->cont->prepare(
                "SELECT SUM(saldo) AS total FROM mahasiswa WHERE id_jurusan=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $mahasiswaBalance = $query->fetch(PDO::FETCH_OBJ)->total;

            $query = $this->cont->prepare(
                "SELECT SUM(saldo) AS total FROM kantin WHERE id_jurusan=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $kantinBalance = $query->fetch(PDO::FETCH_OBJ)->total;

            $query = $this->cont->prepare(
                "SELECT SUM(terkumpul) AS total FROM donasi WHERE id_jurusan=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $donasi = $query->fetch(PDO::FETCH_OBJ)->total;

            return (object)[
                "jurusan" => $jurusanBalance,
                "mahasiswa" => $mahasiswaBalance,
                "kantin" => $kantinBalance,
                "donasi" => $donasi,
                "total" => $mahasiswaBalance + $kantinBalance + $donasi + $jurusanBalance
            ];
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getJurusanMahasiswaStats($id)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT COUNT(id) AS jumlah FROM mahasiswa WHERE jenis_kelamin='laki-laki' AND id_jurusan=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $jumlah_laki = $query->fetch(PDO::FETCH_OBJ)->jumlah;

            $query = $this->cont->prepare(
                "SELECT COUNT(id) AS jumlah FROM mahasiswa WHERE jenis_kelamin='perempuan' AND id_jurusan=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $jumlah_perempuan = $query->fetch(PDO::FETCH_OBJ)->jumlah;

            return (object)["laki" => $jumlah_laki, "perempuan" => $jumlah_perempuan, "total" => $jumlah_laki + $jumlah_perempuan];
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getJurusanTransactions($id)
    {
        try {
            try {
                $query = $this->cont->prepare(
                    "SELECT * FROM transaksi_mahasiswa WHERE id_jurusan=:id"
                );

                $query->bindParam("id", $id, PDO::PARAM_STR);

                $query->execute();

                if ($query->rowCount() > 0) {
                    return $query->fetchAll(PDO::FETCH_OBJ);
                }
            } catch (PDOException $e) {
                exit($e->getMessage());
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getJurusanUKTTransactions($id)
    {
        try {
            try {
                $query = $this->cont->prepare(
                    "SELECT * FROM transaksi_mahasiswa WHERE id_jurusan=:id AND tipe='ukt'"
                );

                $query->bindParam("id", $id, PDO::PARAM_STR);

                $query->execute();

                if ($query->rowCount() > 0) {
                    return $query->fetchAll(PDO::FETCH_OBJ);
                }
            } catch (PDOException $e) {
                exit($e->getMessage());
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function uktWithdrawal($id_jurusan, $amount)
    {
        try {
            $query = $this->cont->prepare(
                "UPDATE jurusan
                SET saldo = IF(:amount <= saldo, saldo - :amount, saldo)
                WHERE id=:id"
            );

            $query->bindParam("id", $id_jurusan, PDO::PARAM_STR);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function changeJurusanBiayaUKT($id_jurusan, $biaya)
    {
        try {
            $query = $this->cont->prepare(
                "UPDATE jurusan
                SET biaya_ukt=:biaya
                WHERE id=:id"
            );

            $query->bindParam("id", $id_jurusan, PDO::PARAM_STR);
            $query->bindParam("biaya", $biaya, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getJurusanStats($id)
    {
        $balance = $this->getJurusanTotalBalance($id);
        $mahasiswa = $this->getJurusanMahasiswaStats($id);
        $trx = $this->getJurusanTransactions($id);

        return (object)["balance" => $balance, "mahasiswa" => $mahasiswa, "trx" => $trx];
    }

    public function registerAdmin($nama, $email, $password, $id_jurusan)
    {
        try {
            $query = $this->cont->prepare(
                "INSERT INTO admin(nama, email, level, password, id_jurusan) 
                VALUES (:nama,:email,'admin',:password,:idjurusan)"
            );

            $enc_password = saltHash($password);

            $query->bindParam("nama", $nama, PDO::PARAM_STR);
            $query->bindParam("email", $email, PDO::PARAM_STR);
            $query->bindParam("password", $enc_password, PDO::PARAM_STR);
            $query->bindParam("idjurusan", $id_jurusan, PDO::PARAM_INT);

            $query->execute();

            return $this->cont->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }


    public function loginAdmin($email, $password)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT id FROM admin 
                WHERE email=:email
                AND password=:password"
            );

            $query->bindParam("email", $email, PDO::PARAM_STR);
            $enc_password = saltHash($password);
            $query->bindParam("password", $enc_password, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                $result = $query->fetch(PDO::FETCH_OBJ);
                return $result->id;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function validateAdminPassword($id, $password)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT id FROM admin 
                WHERE id=:id
                AND password=:password"
            );

            $enc_password = saltHash($password);

            $query->bindParam("id", $id, PDO::PARAM_STR);
            $query->bindParam("password", $enc_password, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }


    public function getAdminById($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT id, nama, email, level, id_jurusan
                FROM admin WHERE id=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetch($rettype);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function addAdminJournal($id_admin, $code, $nilai, $ext1 = "")
    {
        try {
            $query = $this->cont->prepare(
                "INSERT INTO aktivitas_admin(id_jurusan, id_admin, code, nilai, ext_1)
                VALUES (:idjurusan,:idadmin,:code,:nilai,:ext1)"
            );

            $query->bindParam("idjurusan", $this->getAdminById($id_admin, PDO::FETCH_OBJ)->id_jurusan, PDO::PARAM_INT);
            $query->bindParam("idadmin", $id_admin, PDO::PARAM_INT);
            $query->bindParam("code", $code, PDO::PARAM_STR);
            $query->bindParam("nilai", $nilai, PDO::PARAM_INT);
            $query->bindParam("ext1", $ext1, PDO::PARAM_STR);

            $query->execute();

            return $this->cont->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getAdminJournal($id_admin)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM aktivitas_admin WHERE id_admin=:idadmin"
            );

            $query->bindParam("idadmin", $id_admin, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetchAll(PDO::FETCH_OBJ);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getSeluruhMahasiswa($idjurusan)
    {
        try {
            $stmt = $this->cont->prepare(
                "SELECT id, id_jurusan, tanggal_pendaftaran, nama, jenis_kelamin, email, level, jenjang, kelas, prodi, nim, saldo 
                FROM mahasiswa WHERE id_jurusan=:idjurusan ORDER BY id ASC"
            );

            $stmt->bindParam("idjurusan", $idjurusan, PDO::PARAM_INT);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function register($nama, $id_jurusan, $jenis_kelamin, $email, $jenjang, $kelas, $prodi, $nim, $saldo)
    {
        try {
            $query = $this->cont->prepare(
                "INSERT INTO mahasiswa(nama, id_jurusan, jenis_kelamin, email, level, jenjang, kelas, prodi, nim, saldo, password) 
                VALUES (:nama,:idjurusan,:jenis_kelamin,:email,'mahasiswa',:jenjang,:kelas,:prodi,:nim,:saldo,:password)"
            );

            $password = generateRandom();

            $enc_password = saltHash($password);

            $query->bindParam("nama", $nama, PDO::PARAM_STR);
            $query->bindParam("idjurusan", $id_jurusan, PDO::PARAM_INT);
            $query->bindParam("jenis_kelamin", $jenis_kelamin, PDO::PARAM_STR);
            $query->bindParam("email", $email, PDO::PARAM_STR);
            $query->bindParam("jenjang", $jenjang, PDO::PARAM_STR);
            $query->bindParam("kelas", $kelas, PDO::PARAM_STR);
            $query->bindParam("prodi", $prodi, PDO::PARAM_STR);
            $query->bindParam("nim", $nim, PDO::PARAM_STR);
            $query->bindParam("saldo", $saldo, PDO::PARAM_INT);
            $query->bindParam("password", $enc_password, PDO::PARAM_STR);

            $query->execute();

            $id_mahasiswa = $this->cont->lastInsertId();

            $queryukt = "INSERT INTO ukt(id_jurusan, id_mahasiswa, bulan) VALUES";

            $bulan = array('januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember');

            foreach ($bulan as $b) {
                $queryukt .= "('$id_jurusan', '$id_mahasiswa', '$b')" . ($b != "desember" ? "," : "");
            }

            $this->cont->prepare($queryukt)->execute();

            return array($id_mahasiswa, $password);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function editUserFull($id, $nama, $id_jurusan, $jenis_kelamin, $email, $jenjang, $kelas, $prodi, $nim)
    {
        try {
            $query = $this->cont->prepare(
                "UPDATE mahasiswa
                SET nama=:nama,
                jenis_kelamin=:jenis_kelamin,
                email=:email,
                jenjang=:jenjang,
                kelas=:kelas,
                prodi=:prodi,
                nim=:nim WHERE id=:id AND id_jurusan=:idjurusan"
            );

            $query->bindParam("id", $id, PDO::PARAM_INT);
            $query->bindParam("idjurusan", $id_jurusan, PDO::PARAM_INT);
            $query->bindParam("nama", $nama, PDO::PARAM_STR);
            $query->bindParam("jenis_kelamin", $jenis_kelamin, PDO::PARAM_STR);
            $query->bindParam("email", $email, PDO::PARAM_STR);
            $query->bindParam("jenjang", $jenjang, PDO::PARAM_STR);
            $query->bindParam("kelas", $kelas, PDO::PARAM_STR);
            $query->bindParam("prodi", $prodi, PDO::PARAM_STR);
            $query->bindParam("nim", $nim, PDO::PARAM_STR);

            $query->execute();

            return $query->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function login($email, $password)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT id FROM mahasiswa 
                WHERE email=:email
                AND password=:password"
            );

            $enc_password = saltHash($password);

            $query->bindParam("email", $email, PDO::PARAM_STR);
            $query->bindParam("password", $enc_password, PDO::PARAM_STR);

            $query->execute();

            // print_r($query->fetchAll(PDO::FETCH_ASSOC));

            if ($query->rowCount() > 0) {
                $result = $query->fetch(PDO::FETCH_OBJ);
                return $result->id;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function validatePassword($id, $password)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT id FROM mahasiswa 
                WHERE id=:id
                AND password=:password"
            );

            $enc_password = saltHash($password);

            $query->bindParam("id", $id, PDO::PARAM_STR);
            $query->bindParam("password", $enc_password, PDO::PARAM_STR);

            $query->execute();

            return $query->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function changeUserPassword($id, $old_password, $new_password)
    {
        try {
            $query = $this->cont->prepare(
                "UPDATE mahasiswa SET password=IF(password=:old_password, :new_password, password) WHERE id=:id"
            );

            $old_password = saltHash($old_password);
            $new_password = saltHash($new_password);

            $query->bindParam("old_password", $old_password, PDO::PARAM_STR);
            $query->bindParam("new_password", $new_password, PDO::PARAM_STR);
            $query->bindParam("id", $id, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function searchUser($query)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT id, tanggal_pendaftaran, nama, email, level, jenjang, kelas, prodi, nim, saldo
                FROM mahasiswa WHERE name LIKE '%:query%' OR email=':query' OR nim=':query'"
            );

            $query->bindParam("query", $query, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetchAll(PDO::FETCH_OBJ);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getUserById($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT id, id_jurusan, tanggal_pendaftaran, nama, jenis_kelamin, email, level, jenjang, kelas, prodi, nim, saldo 
                FROM mahasiswa WHERE id=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetch($rettype);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getUserByEmail($email, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT id, id_jurusan, tanggal_pendaftaran, nama, jenis_kelamin, email, level, jenjang, kelas, prodi, nim, saldo 
                FROM mahasiswa WHERE email=:email"
            );

            $query->bindParam("email", $email, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetch($rettype);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getUserByNIM($nim, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT id, id_jurusan, tanggal_pendaftaran, nama, jenis_kelamin, email, level, jenjang, kelas, prodi, nim, saldo 
                FROM mahasiswa WHERE nim=:nim"
            );

            $query->bindParam("nim", $nim, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetch($rettype);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getUserUKTBill($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM ukt WHERE id_mahasiswa=:id AND status_pembayaran=0"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetchAll($rettype);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getUserUKT($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM ukt WHERE id_mahasiswa=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetchAll($rettype);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function payUKT($mahasiswaid, $jurusanid, $uktid)
    {
        try {
            $jurusan = $this->getJurusanData($jurusanid, PDO::FETCH_OBJ);
            $jumlah = $jurusan->biaya_ukt;

            $query = $this->cont->prepare(
                "UPDATE jurusan
                SET saldo = saldo + :amount
                WHERE id=:idjurusan"
            );

            $query->bindParam("idjurusan", $jurusanid, PDO::PARAM_STR);
            $query->bindParam("amount", $jumlah, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $query = $this->cont->prepare(
                "UPDATE mahasiswa
                SET saldo = IF(:amount <= saldo, saldo - :amount, saldo)
                WHERE id=:mahasiswaid"
            );

            $query->bindParam("mahasiswaid", $mahasiswaid, PDO::PARAM_STR);
            $query->bindParam("amount", $jumlah, PDO::PARAM_INT);

            $query->execute();


            if ($query->rowCount() < 0) {
                return false;
            }

            $query = $this->cont->prepare(
                "UPDATE ukt SET status_pembayaran=1, tanggal_pembayaran=now() WHERE id=:id"
            );

            $query->bindParam("id", $uktid, PDO::PARAM_INT);

            $query->execute();

            return $query->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function mahasiswaDeposit($mahasiswaid, $amount)
    {
        try {
            $query = $this->cont->prepare(
                "UPDATE mahasiswa
                SET saldo = saldo + :amount
                WHERE id=:mahasiswaid"
            );

            $query->bindParam("mahasiswaid", $mahasiswaid, PDO::PARAM_STR);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function mahasiswaWithdrawal($mahasiswaid, $amount)
    {
        try {
            $query = $this->cont->prepare(
                "UPDATE mahasiswa
                SET saldo = IF(:amount <= saldo, saldo - :amount, saldo)
                WHERE id=:mahasiswaid"
            );

            $query->bindParam("mahasiswaid", $mahasiswaid, PDO::PARAM_STR);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function transferByNIM($mahasiswaid, $nim, $amount)
    {
        try {
            $query = $this->cont->prepare(
                "UPDATE mahasiswa
                SET saldo = IF(:amount <= saldo, saldo - :amount, saldo)
                WHERE id=:mahasiswaid"
            );

            $query->bindParam("mahasiswaid", $mahasiswaid, PDO::PARAM_STR);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $query = $this->cont->prepare(
                "UPDATE mahasiswa
                SET saldo = saldo + :amount
                WHERE nim=:nim"
            );

            $query->bindParam("nim", $nim, PDO::PARAM_STR);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);


            $query->execute();

            if ($query->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getUserTransactionHistory($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT *
                 FROM transaksi_mahasiswa
                 WHERE mahasiswa_id=:id
                 ORDER BY tanggal DESC"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetchAll($rettype);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function createDonasi($judul, $deskripsi, $target, $idposter)
    {
        try {
            $query = $this->cont->prepare(
                "INSERT INTO donasi(judul, deskripsi, diposting_oleh, target_donasi, id_jurusan) 
                VALUES (:judul,:deskripsi,:idposter,:tgt,:idjurusan)"
            );

            $query->bindParam("judul", $judul, PDO::PARAM_STR);
            $query->bindParam("deskripsi", $deskripsi, PDO::PARAM_STR);
            $query->bindParam("tgt", $target, PDO::PARAM_INT);
            $query->bindParam("idposter", $idposter, PDO::PARAM_STR);
            $query->bindParam(
                "idjurusan",
                $this->getAdminById($idposter, PDO::FETCH_OBJ)->id_jurusan,
                PDO::PARAM_INT
            );

            $query->execute();

            return $this->cont->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function changedonasiStatus($id, $adminid, $status)
    {
        try {
            $query = $this->cont->prepare(
                "UPDATE donasi SET status=:status WHERE id=:id AND id_jurusan=:idjurusan"
            );

            $query->bindParam("id", $id, PDO::PARAM_INT);
            $query->bindParam(
                "idjurusan",
                $this->getAdminById($adminid, PDO::FETCH_OBJ)->id_jurusan,
                PDO::PARAM_INT
            );
            $query->bindParam("status", $status, PDO::PARAM_STR);

            $query->execute();

            return $this->cont->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getdonasi($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM donasi WHERE id=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetch($rettype);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getDonatur($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT mahasiswa.nama, mahasiswa.jenjang, mahasiswa.kelas,
                 mahasiswa.prodi, donasi_mahasiswa.jumlah, donasi_mahasiswa.private
                 FROM donasi_mahasiswa INNER JOIN mahasiswa
                 ON donasi_mahasiswa.mahasiswa_id = mahasiswa.id
                 WHERE donasi_mahasiswa.id_donasi=:id
                 ORDER BY tanggal DESC"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetchAll($rettype);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllDonasi($id_jurusan, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM donasi WHERE id_jurusan=:idjurusan"
            );

            $query->bindParam("idjurusan", $id_jurusan, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetchAll($rettype);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function funddonasi($id_donasi, $mahasiswa_id, $amount, $isprivate)
    {
        try {
            $query = $this->cont->prepare(
                "UPDATE donasi
                SET terkumpul = terkumpul + :amount
                WHERE id=:donasiid"
            );

            $query->bindParam("donasiid", $id_donasi, PDO::PARAM_STR);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $query = $this->cont->prepare(
                "UPDATE mahasiswa
                SET saldo = IF(:amount <= saldo, saldo - :amount, saldo)
                WHERE id=:mahasiswaid"
            );

            $query->bindParam("mahasiswaid", $mahasiswa_id, PDO::PARAM_INT);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);


            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $query = $this->cont->prepare(
                "INSERT INTO donasi_mahasiswa(id_donasi, mahasiswa_id, jumlah, private, id_jurusan) 
                VALUES (:donasiid,:mahasiswaid,:amount,:isprivate,:idjurusan)"
            );

            $query->bindParam("donasiid", $id_donasi, PDO::PARAM_INT);
            $query->bindParam("mahasiswaid", $mahasiswa_id, PDO::PARAM_INT);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);
            $query->bindParam("isprivate", $isprivate, PDO::PARAM_BOOL);
            $query->bindParam("idjurusan", $this->getUserById($mahasiswa_id, PDO::FETCH_OBJ)->id_jurusan, PDO::PARAM_INT);

            $query->execute();

            return $this->cont->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function disbursementdonasi($id_donasi, $admin_id, $amount)
    {
        try {
            $query = $this->cont->prepare(
                "UPDATE donasi
                SET terkumpul = IF(:amount <= terkumpul, terkumpul - :amount, terkumpul)
                WHERE id=:donasiid && id_jurusan=:idjurusan"
            );

            $query->bindParam("donasiid", $id_donasi, PDO::PARAM_STR);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);
            $query->bindParam("idjurusan", $this->getAdminById($admin_id, PDO::FETCH_OBJ)->id_jurusan, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function addTransaction($kredit, $type, $jenis, $mahasiswaid, $method, $description)
    {
        try {
            $query = $this->cont->prepare(
                "INSERT INTO transaksi_mahasiswa(kredit, debit, tipe, jenis, mahasiswa_id, metode, deskripsi, id_jurusan)
                VALUES (:kredit,:debit,:tipe,:jenis,:mahasiswaid,:metode,:deskripsi,:idjurusan)"
            );

            $debit = $this->getUserById($mahasiswaid, PDO::FETCH_OBJ)->saldo;

            $query->bindParam("kredit", $kredit, PDO::PARAM_INT);
            $query->bindParam("debit", $debit, PDO::PARAM_INT);
            $query->bindParam("tipe", $type, PDO::PARAM_STR);
            $query->bindParam("jenis", $jenis, PDO::PARAM_STR);
            $query->bindParam("mahasiswaid", $mahasiswaid, PDO::PARAM_STR);
            $query->bindParam("metode", $method, PDO::PARAM_STR);
            $query->bindParam("deskripsi", $description, PDO::PARAM_STR);
            $query->bindParam("idjurusan", $this->getUserById($mahasiswaid, PDO::FETCH_OBJ)->id_jurusan, PDO::PARAM_INT);

            $query->execute();

            return $this->cont->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getTransaction($id, $rettype)
    {
        try {
            try {
                $query = $this->cont->prepare(
                    "SELECT * FROM transaksi_mahasiswa WHERE id=:id"
                );

                $query->bindParam("id", $id, PDO::PARAM_STR);

                $query->execute();

                if ($query->rowCount() > 0) {
                    return $query->fetch($rettype);
                }
            } catch (PDOException $e) {
                exit($e->getMessage());
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function registerKantin($nama, $deskripsi, $id_jurusan)
    {
        try {
            $query = $this->cont->prepare(
                "INSERT INTO kantin(nama, deskripsi, id_jurusan)
                VALUES (:nama,:deskripsi,:idjurusan)"
            );

            $query->bindParam("nama", $nama, PDO::PARAM_STR);
            $query->bindParam("deskripsi", $deskripsi, PDO::PARAM_STR);
            $query->bindParam("idjurusan", $id_jurusan, PDO::PARAM_INT);

            $query->execute();

            return $this->cont->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getKantin($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM kantin WHERE id=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetch($rettype);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getKantinList($id_jurusan, $rettype)
    {
        $stmt = $this->cont->prepare("SELECT * FROM kantin WHERE id_jurusan=:idjurusan");

        $stmt->bindParam("idjurusan", $id_jurusan, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll($rettype);
    }

    public function getTransaksiKantin($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM transaksi_kantin WHERE kantin_id=:id ORDER BY id DESC"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetchAll($rettype);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function kantinWithdrawal($kantinid, $amount)
    {
        try {
            $query = $this->cont->prepare(
                "UPDATE kantin
                SET saldo = IF(:amount <= saldo, saldo - :amount, saldo)
                WHERE id=:kantinid"
            );

            $query->bindParam("kantinid", $kantinid, PDO::PARAM_STR);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function payKantin($mahasiswaid, $uniqueid, $amount)
    {
        try {
            $QR = $this->getQR($uniqueid, PDO::FETCH_OBJ);

            $query = $this->cont->prepare(
                "UPDATE kantin
                SET saldo = saldo + :amount
                WHERE id=:idkantin"
            );

            $query->bindParam("idkantin", $QR->id_kantin, PDO::PARAM_STR);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);

            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $query = $this->cont->prepare(
                "UPDATE mahasiswa
                SET saldo = IF(:amount <= saldo, saldo - :amount, saldo)
                WHERE id=:mahasiswaid"
            );

            $query->bindParam("mahasiswaid", $mahasiswaid, PDO::PARAM_STR);
            $query->bindParam("amount", $amount, PDO::PARAM_INT);


            $query->execute();

            if ($query->rowCount() < 0) {
                return false;
            }

            $query = $this->cont->prepare(
                "INSERT INTO transaksi_kantin(kantin_id, mahasiswa_id, qr_id, jumlah, id_jurusan)
                VALUES (:kantinid,:mahasiswaid,:qrid,:jumlah,:idjurusan)"
            );

            $query->bindParam("kantinid", $QR->id_kantin, PDO::PARAM_INT);
            $query->bindParam("mahasiswaid", $mahasiswaid, PDO::PARAM_INT);
            $query->bindParam("qrid", $QR->id, PDO::PARAM_STR);
            $query->bindParam("jumlah", $amount, PDO::PARAM_INT);
            $query->bindParam("idjurusan", $this->getUserById($mahasiswaid, PDO::FETCH_OBJ)->id_jurusan, PDO::PARAM_INT);

            $query->execute();

            return $this->cont->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function newQr($judul, $nilai, $tetap, $id_admin, $id_kantin)
    {
        try {
            $uniid = "";

            do {
                $uniid = generateRandom();

                $chk = $this->cont->prepare("SELECT * FROM qrcode WHERE unique_id=:uniid");
                $chk->bindParam("uniid", $uniid);
                $chk->execute();
            } while ($chk->rowCount() > 0);


            $query = $this->cont->prepare(
                "INSERT INTO qrcode(judul, nilai, tetap, dibuat_oleh, id_kantin, unique_id, id_jurusan)
                VALUES (:judul,:nilai,:tetap,:id_admin,:id_kantin,:id_unik,:idjurusan)"
            );

            $query->bindParam("judul", $judul, PDO::PARAM_STR);
            $query->bindParam("nilai", $nilai, PDO::PARAM_INT);
            $query->bindParam("tetap", $tetap, PDO::PARAM_BOOL);
            $query->bindParam("id_admin", $id_admin, PDO::PARAM_STR);
            $query->bindParam("id_kantin", $id_kantin, PDO::PARAM_STR);
            $query->bindParam("id_unik", $uniid, PDO::PARAM_STR);
            $query->bindParam("idjurusan", $this->getAdminById($id_admin, PDO::FETCH_OBJ)->id_jurusan, PDO::PARAM_INT);

            $query->execute();

            return $this->cont->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getQR($uniqueid, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM qrcode WHERE unique_id=:unid"
            );

            $query->bindParam("unid", $uniqueid, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetch($rettype);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getQRById($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM qrcode WHERE id=:id"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetch($rettype);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getQRCodeKantin($id, $rettype)
    {
        try {
            $query = $this->cont->prepare(
                "SELECT * FROM qrcode WHERE id_kantin=:id ORDER BY id DESC"
            );

            $query->bindParam("id", $id, PDO::PARAM_STR);

            $query->execute();

            if ($query->rowCount() > 0) {
                return $query->fetchAll($rettype);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function disconnect()
    {
        $this->cont = null;
    }
}
