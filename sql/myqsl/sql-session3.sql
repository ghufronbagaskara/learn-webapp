CREATE DATABASE session_three_db;
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
