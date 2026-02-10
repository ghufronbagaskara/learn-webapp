CREATE DATABASE session_one_db;
USE session_one_db;

CREATE TABLE customers (
  id INT PRIMARY KEY,
  name VARCHAR(100),
  city VARCHAR(100)
);

CREATE TABLE salesman (
  id INT PRIMARY KEY,
  name VARCHAR(100),
  city VARCHAR(100),
  commission DECIMAL(5,2)
);

CREATE TABLE orders (
  id INT PRIMARY KEY,
  order_date DATE,
  amount DECIMAL(10,2),
  customer_id INT,
  salesman_id INT,
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (salesman_id) REFERENCES salesman(id)
);


-- data 
INSERT INTO customers VALUES
(1, 'Simorangkir', 'Malang'),
(2, 'RAHMAN ANDI', 'Surabaya'),
(3, 'citra budi', 'Kediri');

INSERT INTO salesman VALUES
(1, 'Rina', 'Malang', 0.10),
(2, 'Doni', 'Surabaya', 0.12);

INSERT INTO orders VALUES
(1, '2024-01-01', 500000, 1, 1),
(2, '2024-01-03', 300000, 1, 2),
(3, '2024-01-05', 700000, 2, 1);

-- query
-- 1
SELECT c.id, c.name
FROM customers c
LEFT JOIN orders o ON c.id = o.customer_id
WHERE o.id IS NULL;

-- 2
SELECT c.name, SUM(o.amount) AS total_pembelian
FROM customers c
JOIN orders o ON c.id = o.customer_id
GROUP BY c.id;

-- 3
SELECT c.name, COUNT(o.id) AS jumlah_pesanan
FROM customers c
JOIN orders o ON c.id = o.customer_id
GROUP BY c.id;

-- 4
SELECT 
  MAX(amount) AS max_amount,
  MIN(amount) AS min_amount,
  AVG(amount) AS avg_amount
FROM orders;

