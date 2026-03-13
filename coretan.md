

hari 11 - sql session 2 
materi : Hello Maxians ! ? Selamat datang di Bootcamp Backend Programming Day 12. Pada pembelajaran individu, peserta akan mengikuti pembelajaran synchronus, asynchronus, dan praktikum. Pada pertemuan kali ini, peserta akan mengikuti pembelajaran terkait: - Subquery - Struktur Subquery - Aturan Subquery In, Any, Some, All, Exist, Not Exist - Jenis - jenis Subquery, Single row subquery, Multiple row subquery, Correlated subquery, Nested subqueries - Penggunaan Subquery Dengan From, INSERT, Update, dan Delete
soal : Hello Maxians ! ? Gimana, udah pada nonton videonya kan? Sekarang Saatnya kalian untuk mengimplementasi ilmu-ilmu yang sudah kalian tonton sebelumnya!? Buatlah tabel : (Tentukan mana yang menjadi primary key dan foreign key) - Mahasiswa yang isi kolomnya yaitu : id, NIM. name dan alamat - Mata Kuliah yang isi kolomnya yaitu : id, name, sks - Ambil_MK yang isi kolomnya yaitu : id, nilai Soal 1. Buatkan nama mahasiswa dan nilai mata kuliah yang memiliki nilai tertinggi dalam mata kuliah ‘A03’. 2. Dari data mahasiswa yang terdaftar, siapa sajakah mahasiswa yang tidak mengambil matakuliah ‘A01’? 3. Berapakah nilai terendah dari mahasiswa yang bernama Viyella? 4. Jelaskan secara singkat tentang jenis-jenis subquery serta berikan contoh penggunaannya
jawaban : 
CREATE DATABASE session_two_db;
USE session_two_db;

CREATE TABLE mahasiswa (
  id INT PRIMARY KEY,
  nim VARCHAR(20),
  name VARCHAR(100),
  alamat VARCHAR(100)
);

CREATE TABLE mata_kuliah (
  id VARCHAR(10) PRIMARY KEY,
  name VARCHAR(100),
  sks INT
);

CREATE TABLE ambil_mk (
  id INT PRIMARY KEY,
  mahasiswa_id INT,
  mk_id VARCHAR(10),
  nilai INT,
  FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id),
  FOREIGN KEY (mk_id) REFERENCES mata_kuliah(id)
);


INSERT INTO mahasiswa VALUES
(1, '2021001', 'Rahmat Ahmad Madi', 'Papua Barat'),
(2, '2021002', 'Rahman Ahmad Syafii', 'Jakarta'),
(3, '2021003', 'Budi Surahman', 'Kediri');

INSERT INTO mata_kuliah VALUES
('A01', 'Basis Data', 3),
('A02', 'ASD', 3),
('A03', 'Pemrograman Web', 3);

INSERT INTO ambil_mk VALUES
(1, 1, 'A01', 80),
(2, 1, 'A03', 90),
(3, 2, 'A03', 95),
(4, 3, 'A02', 70);

-- soal1
SELECT m.name, a.nilai
FROM mahasiswa m
JOIN ambil_mk a ON m.id = a.mahasiswa_id
WHERE a.mk_id = 'A03'
AND a.nilai = (
  SELECT MAX(nilai)
  FROM ambil_mk
  WHERE mk_id = 'A03'
);

-- soal2
SELECT m.name
FROM mahasiswa m
WHERE m.id NOT IN (
  SELECT mahasiswa_id
  FROM ambil_mk
  WHERE mk_id = 'A01'
);

-- soal3
SELECT MIN(a.nilai) AS nilai_terendah
FROM ambil_mk a
JOIN mahasiswa m ON a.mahasiswa_id = m.id
WHERE m.name = 'Viyella';

/*
-- soal4
subquery types:

1. Single row subquery
   Subquery yang mengembalikan satu baris / satu nilai.
   Biasanya digunakan dengan operator =, >, <.
   Contoh:
   SELECT name
   FROM mahasiswa
   WHERE id = (
     SELECT mahasiswa_id
     FROM ambil_mk
     WHERE nilai = 95
   );

2. Multiple row subquery
   Subquery yang mengembalikan lebih dari satu baris.
   Biasanya digunakan dengan IN, ANY, ALL.
   Contoh:
   SELECT name
   FROM mahasiswa
   WHERE id IN (
     SELECT mahasiswa_id
     FROM ambil_mk
     WHERE mk_id = 'A01'
   );

3. Correlated subquery
   Subquery yang bergantung pada data dari query utama.
   Subquery akan dijalankan berulang untuk setiap baris.
   Contoh:
   SELECT name
   FROM mahasiswa m
   WHERE EXISTS (
     SELECT 1
     FROM ambil_mk a
     WHERE a.mahasiswa_id = m.id
   );

4. Nested subquery
   Subquery yang berada di dalam subquery lainnya.
   Digunakan untuk query bertingkat.
   Contoh:
   SELECT name
   FROM mahasiswa
   WHERE id = (
     SELECT mahasiswa_id
     FROM ambil_mk
     WHERE nilai = (
       SELECT MAX(nilai)
       FROM ambil_mk
     )
   );
*/

hari 12 - sql session 3
- materi : Hello Maxians ! ? Selamat datang di Bootcamp Backend Programming Day 13. Pada pembelajaran individu, peserta akan mengikuti pembelajaran synchronus, asynchronus, dan praktikum. Pada pertemuan kali ini, peserta akan mengikuti pembelajaran terkait: - Implementasi TRIGGERS & STORE PROCEDURE - Implementasi DML Trigger - Implementasi DDL Trigger - Statement Penyusunan Stored Procedure
- soal : Hello Maxians ! ? Gimana, udah pada nonton videonya kan? Sekarang Saatnya kalian untuk mengimplementasi ilmu-ilmu yang sudah kalian tonton sebelumnya!? Buatlah tabel : (Tentukan mana yang menjadi primary key) - Siswa yang isi kolomnya yaitu : id, NIS, nama, TTL, gender, alamat - Nilai yang isi kolomnya yaitu : id, nilai_IPA, nilai_IPS, nilai_MTK Soal 1. Buatlah sebuah procedure dengan nama “getSiswaByBorn” yang digunakan menampilkan data siswa pada tabel “datasiswa” berdasarkan kriteria input tempat lahir! 2. Buatlah sebuah function dengan nama “getJmlByGender” untuk menghitung jumlah siswa pada tabel “datasiswa” berdasarkan kriteria input gender! Triggers Instruksi : Buatlah tabel : (Tentukan mana yang menjadi primary key dan foreign key) - products yang isi kolomnya yaitu : id,nama,harga - stock yang isi kolomnya yaitu : id, quantity Soal 3. Buatlah sebuah trigger yang akan memastikan bahwa setiap kali sebuah produk baru ditambahkan ke dalam tabel Products, informasi terkait produk tersebut juga otomatis dimasukkan ke dalam tabel Stocks dengan nilai awal stok 0. Tuliskan perintah SQL untuk membuat trigger yang memenuhi permintaan tersebut. 4. Sebagai seorang pengembang database, Anda telah membuat sebuah trigger yang akan mengecek stok setiap kali ada perubahan data pada tabel Stocks. Trigger tersebut akan menampilkan pesan peringatan jika stok kurang dari 10. Tuliskan perintah SQL untuk membuat trigger yang mencapai fungsi tersebut.
- jawaban : CREATE DATABASE session_three_db;
USE session_three_db;

-- table for number 1 and 2
CREATE TABLE datasiswa (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nis VARCHAR(20),
  nama VARCHAR(100),
  tempat_lahir VARCHAR(100),
  tanggal_lahir DATE,
  gender ENUM('L', 'P'),
  alamat TEXT
);

CREATE TABLE nilai (
  id INT PRIMARY KEY AUTO_INCREMENT,
  siswa_id INT,
  nilai_IPA INT,
  nilai_IPS INT,
  nilai_MTK INT,
  FOREIGN KEY (siswa_id) REFERENCES datasiswa(id)
);


INSERT INTO datasiswa (nis, nama, tempat_lahir, tanggal_lahir, gender, alamat) VALUES
('001', 'Rahmat Arianto', 'Malang', '2005-01-10', 'L', 'Malang'),
('002', 'Kawal Rahma', 'Surabaya', '2004-03-15', 'L', 'Surabaya'),
('003', 'Yoong Citra', 'Malang', '2005-06-20', 'P', 'Kediri');

INSERT INTO nilai (siswa_id, nilai_IPA, nilai_IPS, nilai_MTK) VALUES
(1, 80, 85, 90),
(2, 75, 70, 88),
(3, 90, 92, 95);

-- soal 1
DELIMITER $$

CREATE PROCEDURE getSiswaByBorn(IN tempatLahir VARCHAR(100))
BEGIN
  SELECT *
  FROM datasiswa
  WHERE tempat_lahir = tempatLahir;
END $$

DELIMITER ;

-- soal 2
DELIMITER $$

CREATE FUNCTION getJmlByGender(jenisGender CHAR(1))
RETURNS INT
DETERMINISTIC
BEGIN
  DECLARE total INT;
  SELECT COUNT(*) INTO total
  FROM datasiswa
  WHERE gender = jenisGender;
  RETURN total;
END $$

DELIMITER ;

-- table for number 3 and 4 
CREATE TABLE products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nama VARCHAR(100),
  harga INT
);

CREATE TABLE stock (
  id INT PRIMARY KEY AUTO_INCREMENT,
  product_id INT,
  quantity INT,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- soal 3
DELIMITER $$

CREATE TRIGGER after_insert_products
AFTER INSERT ON products
FOR EACH ROW
BEGIN
  INSERT INTO stock (product_id, quantity)
  VALUES (NEW.id, 0);
END $$

DELIMITER ;

-- soal 4
DELIMITER $$

CREATE TRIGGER before_update_stock
BEFORE UPDATE ON stock
FOR EACH ROW
BEGIN
  IF NEW.quantity < 10 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'warning: stock nya kurang 10!';
  END IF;
END $$

DELIMITER ;


hari 13 - php session 1 
- materi : Hello Maxians ! ? Selamat datang di Bootcamp Backend Programming Day 14. Pada pembelajaran individu, peserta akan mengikuti pembelajaran synchronus, asynchronus, dan praktikum. Pada pertemuan kali ini, peserta akan mengikuti pembelajaran terkait: Mengenal PHP - Mengenal Variabel yang ada dalam PHP, Variabel Lokal, Variabel Global, Variabel Superglobal, Variabel Statik, Array, Objek - Mengenal Kondisi dalam PHP, IF, ELSE, ELSEIF, SWITCH
- soal : Hello Maxians ! ? Gimana, kalian sudah selesai nonton videonya kan?? Tugas : Sistem Manajemen Perpustakaan Sederhana, Berikut adalah beberapa fitur yang bisa kamu tambahkan: 1. Daftar Buku: Buat array yang berisi daftar buku di perpustakaan. Gunakan loop untuk menampilkan daftar ini kepada pengguna. 2. Peminjaman Buku: Izinkan pengguna untuk meminjam buku. Saat buku dipinjam, hapus dari daftar buku. 3. Pengembalian Buku: Izinkan pengguna untuk mengembalikan buku. Saat buku dikembalikan, tambahkan kembali ke daftar buku. 4. Menu: Buat menu sederhana menggunakan switch yang memungkinkan pengguna untuk memilih antara melihat daftar buku, meminjam buku, atau mengembalikan buku.
- jawaban : <?php
if (!isset($_SESSION['books'])) {
  $_SESSION['books'] = [
    "Laskar Pelangi",
    "Bumi Manusia",
    "Negeri 5 Menara",
    "Dilan 1990",
    "Atomic Habbits"
  ];
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $action = $_POST['action'];
  $book = trim($_POST['book']);

  if ($action == "borrow") {
    if (($key = array_search($book, $_SESSION['books'])) !== false) {
      unset($_SESSION['books'][$key]);
      $message = "Bulu <b>$book</b> berhasil dipinjam.";
    } else {
      $message = "Buku tidak tersedia.";
    }
  }

  if ($action == "return") {
    if (!in_array($book, $_SESSION['books'])) {
      $_SESSION['books'][] = $book;
      $message = "Buku <b>$book</b> berhasil dikembalikkan.";
    } else {
      $message = "Buku sudah ada di perpustakaan";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Sistem Perpustakaan</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

  <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-lg">
    <h1 class="text-2xl font-bold text-center mb-6">
      Perpustakaan Sederhana - Maxy Academy
    </h1>

    <?php if ($message): ?>
      <div class="mb-4 p-3 bg-blue-100 text-blue-700 rounded">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <div class="mb-6">
      <h2 class="font-semibold mb-2">
        Daftar Buku Tersedia
      </h2>

      <?php if (count($_SESSION['books']) > 0): ?>
        <ul class="list-disc pl-5 text-gray-700">
          <?php foreach ($_SESSION['books'] as $b): ?>
            <li><?= htmlspecialchars($b) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="text-red-500">
          Semua buku sedang dipinjam.
        </p>
      <?php endif; ?>
    </div>

    <form method="POST" class="mb-4">
      <input type="hidden" name="action" value="borrow">

      <input
        type="text"
        name="book"
        placeholder="Judul buku yang dipinjam"
        class="w-full p-2 border rounded mb-2"
        required>

      <button
        class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">
        Pinjam Buku
      </button>
    </form>


    <form method="POST">
      <input type="hidden" name="action" value="return">

      <input
        type="text"
        name="book"
        placeholder="Judul buku yang dikembalikan"
        class="w-full p-2 border rounded mb-2"
        required>

      <button
        class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">
        Kembalikan Buku
      </button>
    </form>
  </div>

</body>

</html>

hari 14 - repetition loop
- materi : Hello Maxians ! ?

Selamat datang di Bootcamp Backend Programming Day 15. Pada pembelajaran individu, peserta akan mengikuti pembelajaran synchronus, asynchronus, dan praktikum.

Pada pertemuan kali ini, peserta akan mengikuti pembelajaran terkait:

Loop For
Loop While
Loop Do / While
Loop Foreach
Loop Bersarang
Fungsi dengan Parameter
Fungsi yang mengembalikan nilai
Memanggil Fungsi dalam Fungsi
Fungsi Rekursif
- soal : Hello Maxians ! ?

Gimana, kalian sudah selesai nonton videonya kan??

Sekarang saatnya kalian untuk mengimplementasi ilmu-ilmu yang sudah ditonton sebelumnya, seperti :

Penggunaan Fungsi
Penggunaan Loop
Tugas : Membuat Sistem Penilaian Siswa
- jawaban : <?php
if (!isset($_SESSION['siswa'])) {
  $_SESSION['siswa'] = [
    ["nama" => "Rahmat Arianto", "nilai" => 86],
    ["nama" => "Anjayani Ikhtisamul", "nilai" => 78],
    ["nama" => "Yuni Perkassa", "nilai" => 100],
    ["nama" => "Budianto Santoso", "nilai" => 60],
  ];
}
function getGrade($nilai) {
  if ($nilai >= 85) return "A";
  if ($nilai >= 70) return "B";
  if ($nilai >= 65) return "C";
  return "D";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if ($_POST['action'] === "add") {
    $_SESSION['siswa'][] = [
      "nama" => $_POST['nama'],
      "nilai" => (int)$_POST['nilai']
    ];
  }

  if ($_POST['action'] === "edit") {
    $index = $_POST['index'];
    $_SESSION['siswa'][$index]['nilai'] = (int)$_POST['nilai'];
  }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Sistem Penilaian Siswa</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

  <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-2xl">
    <h1 class="text-2xl font-bold text-center mb-6">
      Sistem Penilaian Siswa
    </h1>

    <table class="w-full border border-gray-300 text-center mb-6">
      <thead class="bg-gray-200">
        <tr>
          <th class="p-2 border">No</th>
          <th class="p-2 border">Nama</th>
          <th class="p-2 border">Nilai</th>
          <th class="p-2 border">Grade</th>
          <th class="p-2 border">Edit</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($_SESSION['siswa'] as $i => $data): ?>
          <tr class="hover:bg-gray-50">
            <td class="p-2 border"><?= $i + 1 ?></td>
            <td class="p-2 border"><?= $data['nama'] ?></td>
            <td class="p-2 border"><?= $data['nilai'] ?></td>
            <td class="p-2 border font-bold"><?= getGrade($data['nilai']) ?></td>
            <td class="p-2 border">
              <form method="POST" class="flex gap-2 justify-center">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="index" value="<?= $i ?>">
                <input
                  type="number"
                  name="nilai"
                  class="w-20 border rounded px-2"
                  required>
                <button class="bg-yellow-500 text-white px-3 rounded hover:bg-yellow-600">
                  Edit
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <h2 class="font-semibold mb-2">Tambah Siswa</h2>
    <form method="POST" class="flex gap-2">
      <input type="hidden" name="action" value="add">

      <input
        type="text"
        name="nama"
        placeholder="Nama siswa"
        class="flex-1 border rounded px-3 py-2"
        required>

      <input
        type="number"
        name="nilai"
        placeholder="Nilai"
        class="w-24 border rounded px-3 py-2"
        required>

      <button class="bg-blue-500 text-white px-4 rounded hover:bg-blue-600">
        Tambah
      </button>
    </form>

  </div>

</body>

</html>

hari 15 - php session 2
- materi : Hello Maxians ! ?

Selamat datang di Bootcamp Backend Programming Day 16. Pada pembelajaran individu, peserta akan mengikuti pembelajaran synchronus, asynchronus, dan praktikum.

Pada pertemuan kali ini, peserta akan mengikuti pembelajaran terkait:

PHP Class OOP
PHP SUBJECT OOP
OOP Constructor and Comment
- soal : Hello Maxians ! ?

Gimana, kalian sudah selesai nonton videonya kan??

Sekarang saatnya kalian untuk mengimplementasi ilmu-ilmu yang sudah ditonton sebelumnya!

Buatlah sebuah sistem sederhana untuk mengelola data buku di perpustakaan dengan menggunakan OOP di PHP. Sistem ini harus memenuhi kriteria berikut yaitu :

Buatlah kelas Buku dengan properti judul, pengarang, tahunTerbit, dan genre. Gunakan constructor untuk menginisialisasi properti-properti ini.

Buatlah metode getDetailBuku() yang akan mengembalikan detail buku dalam format string.
Buatlah kelas Perpustakaan yang memiliki properti lokasi dan daftarBuku (array untuk menyimpan objek-objek dari kelas Buku). Gunakan constructor untuk menginisialisasi properti lokasi.
Buatlah metode tambahBuku($buku) di kelas Perpustakaan untuk menambahkan objek Buku ke dalam daftarBuku.
Buatlah metode getDaftarBuku() di kelas Perpustakaan untuk mencetak daftar buku yang tersedia di perpustakaan.
Buatlah beberapa objek Buku dan tambahkan ke dalam objek Perpustakaan. Cetak daftar buku di perpustakaan
- jawaban : <?php

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

hari 16 - php session 3 
- materi :Hello Maxians ! ?

Selamat datang di Bootcamp Backend Programming Day 17. Pada pembelajaran individu, peserta akan mengikuti pembelajaran synchronus, asynchronus, dan praktikum.

Pada pertemuan kali ini, peserta akan mengikuti pembelajaran terkait:

Membuat Database
Connect Database
FORM INDEX DATA
INPUT Data PHP MYSQL
Delete Data
Free Web Hosting
- soal : Hello Maxians ! ?

Gimana, kalian sudah selesai nonton videonya kan??

Study Case : Crud and Free Web Hosting and Phpmyadmin 

Membuat crud membuat data pasien rumah sakit lalu upload ke web hosting dengan nama domain rumah sakit
- jawaban : # Hospital Patient CRUD System

This project is a simple **CRUD (Create, Read, Update, Delete)** web application for managing **hospital patient data**.  
The system allows users to add, view, edit, and delete patient records using a MySQL database.

---

## 📌 Project Purpose

This project was created as a **submission for the Maxy Academy Bootcamp**, specifically for the study case:
**CRUD, Free Web Hosting, and phpMyAdmin implementation**.

---

## 🛠️ Tech Stack

- **PHP (Native)**
- **MySQL**
- **phpMyAdmin**
- **Apache Web Server**
- **InfinityFree Hosting**

---

## 📂 Features

- Add new patient data
- View patient list
- Edit patient information
- Delete patient data
- Online database management via phpMyAdmin

---

## 🌐 Hosting Information

- **Hosting Provider:** InfinityFree
- **Domain:** https://ghufronbagas.xo.je  
  _(Domain availability depends on DNS propagation)_

---

## 🗄️ Database

- Database Name: `rumah_sakit`
- Table: `pasien`
- Fields:
  - `id` (Primary Key)
  - `nama`
  - `umur`
  - `alamat`
  - `penyakit`

---

## 👤 Author

**Ghufron Bagaskara**


hari 17 - laravel gentella alela
- materi : Hello Maxians ! ?

Selamat datang di Bootcamp Backend Programming Day 18 Pada pembelajaran individu, peserta akan mengikuti pembelajaran synchronus, asynchronus, dan praktikum.

Pada pertemuan kali ini, peserta akan mengikuti pembelajaran terkait:

Pengintegrasian Template HTML Backend ke dalam Laravel
Pembuatan authentication, user management, dan permission management
Pembuatan CRUD Management untuk Master Data dan proses validasi data yang dimasukkan dalam database
Hello Maxians ! ?

Selamat datang di Bootcamp Backend Programming pembelajaran individu, peserta akan mengikuti pembelajaran synchronus, asynchronus, dan praktikum.

Pada pertemuan kali ini, peserta akan mengikuti pembelajaran terkait:

Pembuatan CRUD Management untuk Transaction Data dan proses validasi data yang dimasukkan dalam database
Pembuatan try-catch transaction dan try-catch database serta rollback/commit process
Pembuatan Read (View) yang printable
- soal :Hello Maxians ! ?

Gimana, kalian sudah selesai nonton videonya kan?? 

Sekarang saatnya kalian untuk mengimplementasi ilmu-ilmu yang sudah ditonton sebelumnya, seperti :

Penamaan Class
Pembagian View
Clean Code
Basic security melalui authentication dan permission
Proses validasi yang dilakukan disesuaikan dengan kebutuhan data
Study Case: Sales + Purchasing Cycle Menus, Master Data, and Permission, Transactions
- jawaban : Flow Program: Rapid App Dev - Master Data
Apa ini?
Aplikasi manajemen bisnis berbasis web menggunakan Laravel (PHP Framework) dengan fitur manajemen master data dan transaksi.

Flow Singkat
1. Autentikasi (Login/Register)

User harus login terlebih dahulu
Setelah login, diarahkan ke Dashboard
2. Role & Permission (2 level akses)

Admin — akses penuh: lihat, tambah, edit, hapus semua data
Staff — akses terbatas: hanya bisa lihat & buat transaksi, tidak bisa edit/hapus master data
3. Master Data (dikelola Admin)

Produk — data barang yang dijual/dibeli
Supplier — data pemasok/vendor
Customer — data pelanggan
4. Transaksi

Pembelian (Purchase) — mencatat pembelian produk dari supplier, lengkap dengan item detail
Penjualan (Sale) — mencatat penjualan produk ke customer, lengkap dengan item detail
Tech Stack
Backend: Laravel 11 + Spatie Permission (Role & Permission)
Frontend: Blade Template + Tailwind CSS
Database: MySQL
Alur Data Sederhana
Login → Dashboard → (Admin) Kelola Master Data
                 → (Admin/Staff) Buat Transaksi Pembelian/Penjualan
Singkatnya: aplikasi ini adalah sistem CRUD untuk mengelola data bisnis (produk, supplier, customer) beserta pencatatan transaksi pembelian dan penjualan, dengan kontrol akses berbasis role.

hari 18 - laravel filament
- materi : tentang laravel filament
- soal : Hello Maxians! ✨

Gimana, kalian sudah selesai nonton videonya kan??

Sekarang saatnya kalian untuk mengimplementasikan ilmu-ilmu yang sudah ditonton sebelumnya, kali ini menggunakan Laravel Filament.
Pada tugas ini, kalian akan membuat modul sederhana dengan memanfaatkan fitur CRUD, Table, Relation, dan Action di Filament.

Requirements

Buat Filament Resource untuk SalesOrder yang memiliki field utama:

customer_id (relasi ke Customer)

order_date

total_amount

status

Tambahkan Relation Manager untuk SalesOrderItem (berisi: product_id, qty, price). Relasi ini harus bisa dilihat dan diedit langsung dari halaman Sales Order.

Tambahkan Filter pada table Sales Order untuk:

status (Pending, Paid, Cancelled)

order_date (date range)

Buat Custom Action di halaman Sales Order berupa tombol "Mark as Paid" yang mengubah status menjadi Paid.
- soal : Aplikasi Sales Order Management — Laravel + Filament
Tech Stack: Laravel 11, Filament v3 (admin panel), Laravel Livewire, MySQL

Struktur Data (Models)
Ada 4 entitas utama yang saling berelasi:
Customer  →  SalesOrder  →  SalesOrderItem  ←  Product

Customer — data pelanggan (nama, email, telepon, alamat)
Product — katalog produk dengan harga
SalesOrder — header transaksi (tanggal, status: Pending/Paid/Cancelled, total)
SalesOrderItem — detail item per order (produk, qty, harga, subtotal)
Alur Bisnis
Buat Order — pilih customer, tanggal, status awal Pending
Tambah Items — tambahkan produk ke order; subtotal otomatis dihitung (qty × price) via Eloquent model event (saving)
Total Otomatis — setiap kali item disimpan/dihapus, SalesOrder::calculateTotalAmount() dipanggil otomatis untuk menjumlahkan semua subtotal
Update Status — order bisa diubah ke Paid atau Cancelled
Admin Panel (Filament)
Satu Resource utama: SalesOrderResource — mengelola semua CRUD order
Fitur form: bisa buat Customer baru langsung dari dropdown (inline create)
Relation Manager: ItemsRelationManager untuk kelola item di dalam halaman edit order
Tabel: dilengkapi filter status dan tampilan total amount
Singkatnya
Program ini adalah sistem manajemen transaksi penjualan sederhana — dari pilih pelanggan, input produk, hingga kalkulasi total otomatis — semua dikelola lewat admin panel Filament yang sudah siap pakai.

hari 19 - Seamless Payments Integrating Xendit with Laravel
- materi : Hello maxians! hari ini kita akan belajar tentang implementasi API pembayaran yang sering digunakan digital store, yakni xendit. Maixans akan belajar terkait pengunaan xendit untuk mengimplementasikannya pada projek-projek kalian!

Semangat terus, dan jangan lupa kerjakan tugasnya ya!
- soal : Hello Maxians! ✨

Gimana, kalian sudah selesai nonton videonya kan??

Sekarang saatnya kalian untuk mengimplementasikan ilmu-ilmu yang sudah ditonton sebelumnya, kali ini menggunakan Xendit sebagai payment gateway.
Pada tugas ini, kalian akan mencoba salah satu fitur utama dari Xendit untuk mengintegrasikan pembayaran ke dalam aplikasi Laravel kalian.

Requirements

Setup Xendit di project Laravel kalian menggunakan API key (test mode).

Pilih salah satu fitur Xendit dan implementasikan minimal satu, Contoh:

Invoice API → buat endpoint untuk membuat invoice pembayaran dan tampilkan URL invoice ke user.

Virtual Account (VA) API → buat VA statis/dinamis dan tampilkan nomor rekening virtual ke user.

E-Wallet API → coba generate pembayaran via OVO/DANA/LinkAja test.

 

Simpan response dari Xendit ke dalam database (misalnya payments table) agar status pembayaran bisa dilacak.

Buat halaman sederhana (bisa pakai Blade atau Filament) untuk menampilkan data transaksi pembayaran (status, amount, reference ID).

Study Case: Implementasi Gateway Pembayaran Menggunakan Xendit 

Bisa kalian lakukan sebagai pengembangan dari tugas-tugas sebelumnya
- jawaban : 
Lara-Xendit — Payment Gateway Integration
Tech Stack: Laravel + Xendit API + Tailwind CSS

Flow Program
1. Order (Pesanan)

User membuat order baru dengan mengisi: nama, email, nomor telepon, jumlah tagihan, dan deskripsi.
Order disimpan ke database dengan status awal pending.
2. Buat Pembayaran

Dari halaman detail order, user klik tombol "Pay Now".
PaymentController memanggil XenditService untuk membuat Invoice di Xendit API.
Xendit mengembalikan URL invoice, dan user di-redirect ke halaman pembayaran Xendit.
3. Proses Pembayaran di Xendit

User melakukan pembayaran di halaman Xendit (bisa via transfer bank, e-wallet, dsb).
Setelah bayar, Xendit me-redirect user ke:
/payments/success-page jika berhasil
/payments/failed-page jika gagal
4. Update Status

Saat redirect ke success page, status payment otomatis dicek ke Xendit API.
Status di database diperbarui: pending → paid / failed / expired.
User juga bisa manual klik "Check Status" di halaman detail payment.
5. Relasi Data

Satu Order bisa punya banyak Payment (misal jika gagal lalu coba lagi).
Order dianggap lunas jika salah satu payment-nya berstatus paid.
Intinya: User buat order → sistem buatkan invoice di Xendit → user bayar → status otomatis terupdate.