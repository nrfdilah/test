<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../component/things.php" ?>
    <title>Aktivitas Admin</title>
</head>

<body>
    <?php include "../process/getAdminLoginData.php" ?>
    <?php include "../component/admin/sidebaropen.php" ?>

    <?php        
        $journal = $db->getAdminJournal($data["id"])
    ?>

    <h1>Aktivitas Admin</h1>

    <?php if ($journal) { ?>
        <div class="table-responsive mt-4">
            <table id="adminJournalTable" class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Nilai</th>
                        <th>EXT 1</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($journal as $val) {

                        preg_match('/\w(mahasiswa|mahasiswa)/', $val->code, $ext1mahasiswa);
                        preg_match('/generate_qr_kantin/', $val->code, $ext1qr);
                        preg_match('/tarik_tunai_kantin/', $val->code, $ext1kantin);
                        preg_match('/change_donasi_status/', $val->code, $ext1donasi);
                        preg_match('/tarik_ukt/', $val->code, $ext1admin);

                        ?>

                        <tr>
                            <td><?= $val->id ?></td>
                            <td><?= $val->tanggal ?></td>
                            <td><?= $val->code ?></td>
                            <td data-sort="<?= $val->nilai ?>"><?= $ext1donasi ? $val->nilai : rupiah($val->nilai) ?></td>
                            <td>
                                <?php if (count($ext1mahasiswa)) { ?>
                                    <a href='detail_mahasiswa.php?id=<?= $val->ext_1 ?>'><?= $val->ext_1 ?></a>
                                <?php } else if (count($ext1qr)) {
                                $qr = $db->getQRById($val->ext_1, PDO::FETCH_OBJ);
                                ?>
                                    <a href='info_kantin.php?id=<?= $qr->id_kantin ?>'><?= $val->ext_1 ?></a>
                                <?php } else if (count($ext1donasi)) { ?>
                                    <a href='infodonasi.php?id_donasi=<?= $val->ext_1 ?>'><?= $val->ext_1 ?></a>
                                <?php } else if (count($ext1kantin)) { ?>
                                    <a href='info_kantin.php?id=<?= $val->ext_1 ?>'><?= $val->ext_1 ?></a>
                                <?php } else { ?>
                                    <?= $val->ext_1 ?>
                                <?php } ?>
                            </td>
                        </tr>

                    <?php

                }
                ?>

                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <p>Belum terdapat aktifitas</p>
    <?php } ?>

    <?php include "../component/mahasiswa/sidebarclose.php" ?>
    <?php include "../component/scripts.php" ?>
    <?php $noback = true; require "../component/scrollTop.php" ?>

    <script>
        $(document).ready(function() {
            $('#adminJournalTable').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Maaf, tidak dapat menemukan apapun",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_ halaman",
                    "infoEmpty": "Tidak ada data yang dapat ditampilkan",
                    "infoFiltered": "(tersaring dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    },
                }
            });
        });
    </script>
</body>

</html>