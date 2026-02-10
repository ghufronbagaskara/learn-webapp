<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
  mysqli_query($conn, "INSERT INTO pasien VALUES(
    NULL,
    '$_POST[nama]',
    '$_POST[umur]',
    '$_POST[alamat]',
    '$_POST[penyakit]'
  )");
  header("Location:index.php");
}
?>

<h2>Tambah Pasien</h2>

<form method="post">
  Nama <br>
  <input type="text" name="nama" required><br><br>

  Umur <br>
  <input type="number" name="umur" required><br><br>

  Alamat <br>
  <textarea name="alamat"></textarea><br><br>

  Penyakit <br>
  <input type="text" name="penyakit"><br><br>

  <button type="submit" name="simpan">Simpan</button>
</form>