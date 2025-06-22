-- Veritabanı oluşturma
CREATE DATABASE fhc CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE fhc;

-- Kullanıcılar tablosu
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','editor','authority') NOT NULL DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Şifreler: editör123, yetkili123, admin123
INSERT INTO users (username, password, role) VALUES
('editör', '$2y$12$iM8a7Ih7tiaffeVrivR8Uu7QwrJKOw.RFPJJ9xrPIRlvOj0P0OJXa', 'editor'),
('yetkili', '$2y$12$fXSzbt93Mfsl7.DB9aLLn.VydAINlNmPbCbJEE04Oh0q20QPchETS', 'authority'),
('admin', '$2y$12$Mgq.iIpNqJ/I3oaYcHt1yeUEwN3X9GOJuhT3z3Cd2wEcMI3U6GQNe', 'admin');

-- Site Ayarları Tablosu
CREATE TABLE IF NOT EXISTS site_ayar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_ismi VARCHAR(255) NOT NULL
);

-- Çalışma Saatleri Tablosu
CREATE TABLE IF NOT EXISTS calisma_saatleri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    foto VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hizmetler Tablosu
CREATE TABLE IF NOT EXISTS hizmetler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    baslik VARCHAR(255) NOT NULL,
    aciklama TEXT
);

-- Footer Ayarları Tablosu
CREATE TABLE IF NOT EXISTS footer_ayar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    adres VARCHAR(255),
    iletisim_no VARCHAR(50),
    mail VARCHAR(100),
    hsm_adresi VARCHAR(255),
    tsm_adresi VARCHAR(255),
    copyright_yazisi VARCHAR(255)
);

-- Çalışanlar Tablosu
CREATE TABLE IF NOT EXISTS calisanlar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(100) NOT NULL,
    soyad VARCHAR(100) NOT NULL,
    pozisyon VARCHAR(100),
    departman ENUM('aile_hekimleri','aile_sagligi_calisanlari','yardimci_personel') NOT NULL,
    telefon VARCHAR(20),
    email VARCHAR(100),
    foto VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Galeri Tablosu
CREATE TABLE IF NOT EXISTS galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    foto VARCHAR(255) NOT NULL,
    baslik VARCHAR(255),
    aciklama TEXT,
    sira INT DEFAULT 0,
    durum ENUM('aktif','pasif') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); 

-- Site ayarları tablosuna site_basligi sütunu ekle
ALTER TABLE site_ayar ADD COLUMN site_basligi VARCHAR(255) NULL AFTER site_ismi;

-- Users tablosuna aktif sütunu ekle (kullanıcı aktif/pasif durumu için)
ALTER TABLE users ADD COLUMN aktif TINYINT(1) NOT NULL DEFAULT 1 AFTER role;

-- Mevcut kullanıcıları aktif yap
UPDATE users SET aktif = 1 WHERE aktif IS NULL OR aktif = 0;