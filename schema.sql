-- =========================================================
-- Database Inventaris (Tugas Rutin 8)
-- 3 tabel: kategori, supplier, produk (+ foreign key)
-- =========================================================

CREATE DATABASE IF NOT EXISTS inventaris_db;
USE inventaris_db;

DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS supplier;

CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE supplier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL,
    telepon VARCHAR(20),
    alamat VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(150) NOT NULL,
    kategori_id INT NOT NULL,
    supplier_id INT NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    harga DECIMAL(12, 2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_produk_kategori FOREIGN KEY (kategori_id)
        REFERENCES kategori(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_produk_supplier FOREIGN KEY (supplier_id)
        REFERENCES supplier(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------- Data seed ----------
INSERT INTO kategori (nama_kategori) VALUES
('Elektronik'),
('Alat Tulis'),
('Furnitur'),
('Makanan & Minuman'),
('Kebersihan');

INSERT INTO supplier (nama_supplier, telepon, alamat) VALUES
('PT Sinar Elektronik', '061-4521234', 'Jl. Gatot Subroto No. 12, Medan'),
('CV Maju Jaya', '061-7894561', 'Jl. Sisingamangaraja No. 45, Medan'),
('UD Berkah Abadi', '0812-6543-2100', 'Jl. Thamrin No. 8, Medan'),
('PT Sumber Rezeki', '061-6612345', 'Jl. Iskandar Muda No. 30, Medan'),
('Toko Serba Ada', '0813-7000-1122', 'Jl. Pancing No. 99, Medan');

INSERT INTO produk (nama_produk, kategori_id, supplier_id, stok, harga) VALUES
('Mouse Wireless', 1, 1, 25, 85000),
('Pulpen Gel Hitam (1 lusin)', 2, 2, 40, 36000),
('Kursi Lipat Besi', 3, 3, 10, 175000),
('Air Mineral 600ml (1 dus)', 4, 4, 30, 48000),
('Sabun Cuci Piring 800ml', 5, 5, 50, 15500),
('Keyboard USB', 1, 1, 15, 120000),
('Buku Tulis 58 Lembar (1 pak)', 2, 5, 60, 42000);
