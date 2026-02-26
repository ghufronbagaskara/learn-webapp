<?php

class Buku {
  public string $judul;
  public string $pengarang;
  public int $tahunTerbit;
  public string $genre;

  public function __construct($judul, $pengarang, $tahunTerbit, $genre) {
    $this->judul = $judul;
    $this->pengarang = $pengarang;
    $this->tahunTerbit = $tahunTerbit;
    $this->genre = $genre;
  }

  public function getDetailBuku(): string {
    return "{$this->judul} | {$this->pengarang} | {$this->tahunTerbit} | {$this->genre}";
  }
}

class Perpustakaan {
  public string $lokasi;
  public array $daftarBuku = [];

  public function __construct($lokasi) {
    $this->lokasi = $lokasi;
  }

  public function tambahBuku(Buku $buku): void {
    $this->daftarBuku[] = $buku;
  }

  public function getDaftarBuku(): array {
    return $this->daftarBuku;
  }
}

$perpustakaan = new Perpustakaan("Perpustakaan Kota Malang");

$perpustakaan->tambahBuku(new Buku(
  "Laskar Pelangi",
  "Andrea Hirata",
  2005,
  "Fiksi"
));

$perpustakaan->tambahBuku(new Buku(
  "Bumi Manusia",
  "Pramoedya",
  1980,
  "Sejarah"
));

$perpustakaan->tambahBuku(new Buku(
  "Atomic Habits",
  "James Clear",
  2018,
  "Pengembagan Diri"
));

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Sistem Perpustakaan OOP</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

  <header class="bg-blue-600 text-white py-6 mb-8">
    <div class="max-w-5xl mx-auto px-4">
      <h1 class="text-3xl font-bold">📚 Sistem Manajemen Perpustakaan</h1>
      <p class="opacity-90 mt-1">
        <?= $perpustakaan->lokasi ?>
      </p>
    </div>
  </header>

  <main class="max-w-5xl mx-auto px-4">
    <h2 class="text-xl font-semibold mb-4">Daftar Buku Tersedia</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($perpustakaan->getDaftarBuku() as $buku): ?>
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-5">
          <h3 class="text-lg font-bold mb-2">
            <?= $buku->judul ?>
          </h3>

          <p class="text-sm text-gray-600 mb-1">
            <span class="font-semibold">Pengarang:</span>
            <?= $buku->pengarang ?>
          </p>

          <p class="text-sm text-gray-600 mb-1">
            <span class="font-semibold">Tahun Terbit:</span>
            <?= $buku->tahunTerbit ?>
          </p>

          <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
            <?= $buku->genre ?>
          </span>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

</body>

</html>