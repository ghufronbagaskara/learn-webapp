<?php
include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM pasien WHERE id=$id");
$row = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
  mysqli_query($conn, "UPDATE pasien SET
    nama='$_POST[nama]',
    umur='$_POST[umur]',
    alamat='$_POST[alamat]',
    penyakit='$_POST[penyakit]'
    WHERE id=$id
  ");
  header("Location:index.php");
}
?>

<h2>Edit Pasien</h2>

<form method="post">
  Nama <br>
  <input type="text" name="nama" value="<?= $row['nama'] ?>"><br><br>

  Umur <br>
  <input type="number" name="umur" value="<?= $row['umur'] ?>"><br><br>

  Alamat <br>
  <textarea name="alamat"><?= $row['alamat'] ?></textarea><br><br>

  Penyakit <br>
  <input type="text" name="penyakit" value="<?= $row['penyakit'] ?>"><br><br>

  <button type="submit" name="update">Update</button>
</form>