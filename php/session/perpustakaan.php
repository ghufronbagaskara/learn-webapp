<?php
$buku = ["Laskar Pelangi", "Bumi Manusi", "Negeri 5 Menara", "Dilan 1990"];

echo "--- Sistem Perpustakaan ---\n";
echo "1. Lihat Daftar Buku \n";
echo "2. Meminjam Buku \n";
echo "3. Mengembalikan Buku \n";
echo "Pilih Menu (1-3): ...";

$menu = trim(fgets(STDIN));

switch ($menu) {
  case 1:
    echo "Daftar Buku: \n";
    foreach ($buku as $index => $judul) {
      echo ($index + 1) . ". " . $judul . "\n";
    }
    break;

  case 2:
    echo "\n Masukkan nomor buku yang ingin dipinjam: ";
    $pinjam = trim(fgets(STDIN)) - 1;

    if (isset($buku[$pinjam])){
      echo "Buku \"" . $buku[$pinjam] . "\" berhasil dipinjam.\n";
      unset($buku[$pinjam]);
      $buku = array_values($buku);
    } else {
      echo "Nomor buku tidak valid.\n";
    }
    break;
  
  case 3:
    echo "\n Masukkan judul buku yang ingin dikembalikan: ";
    $kembali = trim(fgets(STDIN));
    $buku[] = $kembali;
    echo "Buku \"$kembali\" berhasil dikembalikan.\n";
    break;
    
  default:
    echo "Masukkan angka yang valid.\n";
    break;
}
