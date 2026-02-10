<?php
include 'koneksi.php';

$data = mysqli_query($conn, "SELECT * FROM pasien");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Pasien Rumah Sakit</title>
</head>
<body>
  <h2>Data Pasien Rumah Sakit</h2>
  <a href="tambah.php">+ Tambah Pasien</a>

  <table border="1" cellpadding="10" cellspacing="0">
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Alamat</th>
      <th>Umur</th>
      <th>Aksi</th>
    </tr>

    <?php $no=1; while($row = mysqli_fetch_assoc($data)) { ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $row['nama']; ?></td>
        <td><?= $row['alamat']; ?></td>
        <td><?= $row['umur']; ?></td>
        <td>
          <a href="edit.php?id=<?= $row['id']; ?>">Edit</a> |
          <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Hapus data?')">Hapus</a>
        </td>
      </tr>
    <?php } ?>
  </table>
  
</body>
</html>