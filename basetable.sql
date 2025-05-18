--  File: topsis_smartphone.sql
--  Description: SQL script to create tables for TOPSIS method in smartphone selection

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'user') NOT NULL
);

-- Admin default
INSERT INTO users (username, password, role) VALUES
('admin', MD5('admin123'), 'admin'),
('user', MD5('user123'), 'user');


-- Tabel untuk menyimpan data smartphone
CREATE TABLE hp (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100)
);

INSERT INTO hp (id, nama) VALUES
(1, 'Redmi 10'),
(2, 'Samsung A03'),
(3, 'Realme C21'),
(4, 'Infinix Hot 11'),
(5, 'Vivo Y12');

-- Tabel untuk menyimpan data kriteria topsis
CREATE TABLE kriteria (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  bobot FLOAT,         -- bobot antar 0 - 1
  tipe ENUM('benefit', 'cost')  -- tipe kriteria
);

INSERT INTO kriteria (id, nama, bobot, tipe) VALUES
(1, 'RAM (GB)', 0.25, 'benefit'),
(2, 'Baterai (mAh)', 0.25, 'benefit'),
(3, 'Kamera (MP)', 0.30, 'benefit'),
(4, 'Harga (juta)', 0.20, 'cost');

-- Tabel untuk menyimpan nilai dari smartphone berdasarkan kriteria
CREATE TABLE nilai_hp (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hp_id INT,
  kriteria_id INT,
  nilai FLOAT,
  FOREIGN KEY (hp_id) REFERENCES hp(id),
  FOREIGN KEY (kriteria_id) REFERENCES kriteria(id)
);

INSERT INTO nilai_hp (hp_id, kriteria_id, nilai) VALUES
(1, 1, 4), (1, 2, 5000), (1, 3, 50), (1, 4, 2.5),
(2, 1, 3), (2, 2, 5000), (2, 3, 48), (2, 4, 2.0),
(3, 1, 4), (3, 2, 5000), (3, 3, 13), (3, 4, 1.9),
(4, 1, 4), (4, 2, 5200), (4, 3, 13), (4, 4, 1.8),
(5, 1, 3), (5, 2, 5000), (5, 3, 13), (5, 4, 1.5);

-- Tabel untuk menyimpan konversi nilai dari detail nilai smartphone
CREATE TABLE skala_nilai (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kriteria_id INT,
  nilai_min FLOAT,
  nilai_max FLOAT,
  poin INT,
  FOREIGN KEY (kriteria_id) REFERENCES kriteria(id)
);

INSERT INTO skala_nilai (kriteria_id, nilai_min, nilai_max, poin) VALUES
-- RAM
(1, 0, 1.99, 1),
(1, 2, 2.99, 2),
(1, 3, 3.99, 3),
(1, 4, 4.99, 4),
(1, 5, 999, 5),
-- Baterai
(2, 0, 2999, 1),
(2, 3000, 3999, 2),
(2, 4000, 4999, 3),
(2, 5000, 5999, 4),
(2, 6000, 9999, 5),
-- Kamera
(3, 0, 10, 1),
(3, 11, 20, 2),
(3, 21, 30, 3),
(3, 31, 40, 4),
(3, 41, 100, 5),
-- Harga (cost)
(4, 0, 1.49, 5),
(4, 1.5, 1.99, 4),
(4, 2, 2.49, 3),
(4, 2.5, 2.99, 2),
(4, 3, 99.99, 1);

-- Tabel untuk menyimpan hasil perhitungan TOPSIS (Nilai Preferensi)
CREATE TABLE hasil_topsis (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hp_id INT,
  skor FLOAT,
  peringkat INT,
  FOREIGN KEY (hp_id) REFERENCES hp(id)
);
