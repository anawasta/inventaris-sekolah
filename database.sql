-- Struktur tabel database aplikasi inventaris

CREATE DATABASE IF NOT EXISTS sarpras_smkn1ptb;
USE sarpras_smkn1ptb;

-- Tabel pengguna
CREATE TABLE pengguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    username VARCHAR(50) UNIQUE,
    password VARCHAR(100),
    level ENUM('admin', 'petugas') DEFAULT 'petugas'
);

-- Data awal pengguna
INSERT INTO pengguna (nama, username, password, level)
VALUES ('Administrator', 'admin', MD5('admin123'), 'admin');

-- Tabel tanah
CREATE TABLE tanah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    lokasi VARCHAR(100),
    luas DOUBLE,
    keterangan TEXT
);

-- Tabel ruang
CREATE TABLE ruang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    kode_ruang VARCHAR(50),
    lokasi VARCHAR(100),
    keterangan TEXT
);

-- Tabel asset
CREATE TABLE asset (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(100),
    kode_barang VARCHAR(50),
    jumlah INT,
    kondisi VARCHAR(50),
    lokasi VARCHAR(100),
    keterangan TEXT
);

-- Tabel BHP
CREATE TABLE bhp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(100),
    jumlah INT,
    satuan VARCHAR(50),
    tanggal_masuk DATE,
    keterangan TEXT
);

-- Tabel peminjaman
CREATE TABLE peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_peminjam VARCHAR(100),
    nama_barang VARCHAR(100),
    jumlah INT,
    tanggal_pinjam DATE,
    tanggal_kembali DATE,
    status ENUM('dipinjam', 'dikembalikan') DEFAULT 'dipinjam'
);
