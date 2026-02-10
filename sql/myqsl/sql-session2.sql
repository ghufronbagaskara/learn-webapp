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
