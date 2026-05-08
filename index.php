<?php
include 'koneksi.php';

// simpan, update, hapus data
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $tahun = $_POST['tahun_terbit'];
    $foto_lama = $_POST['foto_lama'];
    $foto_final = $foto_lama;

    if ($_FILES['foto']['name'] != "") {
        $ekstensi = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_baru = uniqid() . "." . $ekstensi;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], "assets/" . $nama_baru)) {
            $foto_final = $nama_baru;
            if ($id && file_exists("assets/" . $foto_lama)) unlink("assets/" . $foto_lama);
        }
    }

    if ($id == "") {
        mysqli_query($conn, "INSERT INTO buku VALUES (NULL, '$judul', '$pengarang', '$tahun', '$foto_final')");
    } else {
        mysqli_query($conn, "UPDATE buku SET judul='$judul', pengarang='$pengarang', tahun_terbit='$tahun', foto='$foto_final' WHERE id='$id'");
    }
    header("Location: index.php");
}

if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT foto FROM buku WHERE id='$id_hapus'"));
    if (file_exists("assets/" . $data['foto'])) unlink("assets/" . $data['foto']);
    mysqli_query($conn, "DELETE FROM buku WHERE id='$id_hapus'");
    header("Location: index.php");
}

// edit data
$id_edit = $judul_e = $pengarang_e = $tahun_e = $foto_e = "";
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $data_e = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM buku WHERE id='$id_edit'"));
    $judul_e = $data_e['judul'];
    $pengarang_e = $data_e['pengarang'];
    $tahun_e = $data_e['tahun_terbit'];
    $foto_e = $data_e['foto'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Novelist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Katalog Novelist</h1>

        <section class="form-section">
            <h3><?= $id_edit ? "Edit Buku" : "Tambah Buku"; ?></h3>
            <form action="index.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                <input type="hidden" name="id" value="<?= $id_edit; ?>">
                <input type="hidden" name="foto_lama" value="<?= $foto_e; ?>">
                
                <input type="text" name="judul" id="judul" placeholder="Judul Buku" value="<?= $judul_e; ?>">
                <input type="text" name="pengarang" id="pengarang" placeholder="Pengarang" value="<?= $pengarang_e; ?>">
                <input type="number" name="tahun_terbit" id="tahun_terbit" placeholder="Tahun" value="<?= $tahun_e; ?>">
                <input type="file" name="foto" id="foto">
                
                <button type="submit" name="simpan" class="btn btn-tambah">Simpan Data</button>
                <?php if($id_edit): ?> <a href="index.php" class="btn btn-hapus">Batal</a> <?php endif; ?>
            </form>
        </section>

        <hr>

        <table>
    <thead>
        <tr>
            <th>Judul Buku</th>
            <th>Nama Pengarang</th>
            <th>Tahun Terbit</th>
            <th>Foto Sampul</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $res = mysqli_query($conn, "SELECT * FROM buku ORDER BY id DESC");
        while($row = mysqli_fetch_assoc($res)): 
        ?>
        <tr>
            <td><?= $row['judul']; ?></td>
            <td><?= $row['pengarang']; ?></td>
            <td><?= $row['tahun_terbit']; ?></td>
            <td>
                <img src="assets/<?= $row['foto']; ?>" class="thumbnail" alt="Sampul">
            </td>
            <td>
                <a href="index.php?edit=<?= $row['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="index.php?hapus=<?= $row['id']; ?>" class="btn btn-hapus" onclick="return konfirmasiHapus()">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
    </div>
    <script src="script.js"></script>
</body>
</html>