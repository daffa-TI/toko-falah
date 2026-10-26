CREATE DATABASE toko_Falah;
USE toko_Falah;

-- 1. TABEL TANPA FOREIGN KEY

CREATE TABLE satuan (
    idsatuan INT NOT NULL AUTO_INCREMENT,
    nama_satuan VARCHAR(45) NOT NULL,
    status TINYINT NOT NULL DEFAULT 1,
    PRIMARY KEY (idsatuan)
);

CREATE TABLE role (
    idrole INT NOT NULL AUTO_INCREMENT,
    nama_role VARCHAR(100) NOT NULL,
    PRIMARY KEY (idrole)
);

-- badan_hukum: Y = berbadan hukum, T = tidak berbadan hukum
-- status: A = aktif, N = nonaktif
CREATE TABLE vendor (
    idvendor INT NOT NULL AUTO_INCREMENT,
    nama_vendor VARCHAR(100) NOT NULL,
    badan_hukum CHAR(1) NOT NULL,
    status CHAR(1) NOT NULL DEFAULT 'A',
    PRIMARY KEY (idvendor)
);

-- 2. TABEL MASTER DENGAN FOREIGN KEY

CREATE TABLE user (
    iduser INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(45) NOT NULL,
    password VARCHAR(100) NOT NULL,
    idrole INT NOT NULL,
    PRIMARY KEY (iduser),
    CONSTRAINT fk_user_role FOREIGN KEY (idrole) REFERENCES role(idrole)
);

-- buat ngatur ulang kalo kena auto increment
UPDATE `user` SET iduser = 5 WHERE iduser = 9;
ALTER TABLE `user` AUTO_INCREMENT = 6;


-- jenis: A = Sirup, B = Hampers/Paket
CREATE TABLE barang (
    idbarang INT NOT NULL AUTO_INCREMENT,
    jenis CHAR(1) NOT NULL,
    nama VARCHAR(45) NOT NULL,
    idsatuan INT NOT NULL,
    status TINYINT NOT NULL DEFAULT 1,
    harga INT NOT NULL,
    PRIMARY KEY (idbarang),
    CONSTRAINT fk_barang_satuan FOREIGN KEY (idsatuan) REFERENCES satuan(idsatuan)
);

CREATE TABLE margin_penjualan (
    idmargin_penjualan INT NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    persen DOUBLE NOT NULL,
    status TINYINT NOT NULL DEFAULT 1,
    iduser INT NOT NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (idmargin_penjualan),
    CONSTRAINT fk_margin_user FOREIGN KEY (iduser) REFERENCES user(iduser)
);

-- 3. TRANSAKSI PENGADAAN

CREATE TABLE pengadaan (
    idpengadaan BIGINT NOT NULL AUTO_INCREMENT,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    user_iduser INT NOT NULL,
    status CHAR(1) NOT NULL DEFAULT '1',
    vendor_idvendor INT NOT NULL,
    subtotal_nilai INT NOT NULL DEFAULT 0,
    ppn INT NOT NULL DEFAULT 0,
    total_nilai INT NOT NULL DEFAULT 0,
    PRIMARY KEY (idpengadaan),
    CONSTRAINT fk_pengadaan_user FOREIGN KEY (user_iduser) REFERENCES user(iduser),
    CONSTRAINT fk_pengadaan_vendor FOREIGN KEY (vendor_idvendor) REFERENCES vendor(idvendor)
);

CREATE TABLE detail_pengadaan (
    iddetail_pengadaan BIGINT NOT NULL AUTO_INCREMENT,
    harga_satuan INT NOT NULL,
    jumlah INT NOT NULL,
    sub_total INT NOT NULL,
    idbarang INT NOT NULL,
    idpengadaan BIGINT NOT NULL,
    PRIMARY KEY (iddetail_pengadaan),
    CONSTRAINT fk_detpengadaan_barang FOREIGN KEY (idbarang) REFERENCES barang(idbarang),
    CONSTRAINT fk_detpengadaan_pengadaan FOREIGN KEY (idpengadaan) REFERENCES pengadaan(idpengadaan)
);

-- 4. TRANSAKSI PENERIMAAN

CREATE TABLE penerimaan (
    idpenerimaan BIGINT NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status CHAR(1) NOT NULL DEFAULT '1',
    idpengadaan BIGINT NOT NULL,
    iduser INT NOT NULL,
    PRIMARY KEY (idpenerimaan),
    CONSTRAINT fk_penerimaan_pengadaan FOREIGN KEY (idpengadaan) REFERENCES pengadaan(idpengadaan),
    CONSTRAINT fk_penerimaan_user FOREIGN KEY (iduser) REFERENCES user(iduser)
);

CREATE TABLE detail_penerimaan (
    iddetail_penerimaan BIGINT NOT NULL AUTO_INCREMENT,
    idpenerimaan BIGINT NOT NULL,
    barang_idbarang INT NOT NULL,
    jumlah_terima INT NOT NULL,
    harga_satuan_terima INT NOT NULL,
    sub_total_terima INT NOT NULL,
    PRIMARY KEY (iddetail_penerimaan),
    CONSTRAINT fk_detpenerimaan_penerimaan FOREIGN KEY (idpenerimaan) REFERENCES penerimaan(idpenerimaan),
    CONSTRAINT fk_detpenerimaan_barang FOREIGN KEY (barang_idbarang) REFERENCES barang(idbarang)
);

-- 5. TRANSAKSI RETUR

CREATE TABLE retur (
    idretur BIGINT NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    idpenerimaan BIGINT NOT NULL,
    iduser INT NOT NULL,
    PRIMARY KEY (idretur),
    CONSTRAINT fk_retur_penerimaan FOREIGN KEY (idpenerimaan) REFERENCES penerimaan(idpenerimaan),
    CONSTRAINT fk_retur_user FOREIGN KEY (iduser) REFERENCES user(iduser)
);

CREATE TABLE detail_retur (
    iddetail_retur INT NOT NULL AUTO_INCREMENT,
    jumlah INT NOT NULL,
    alasan VARCHAR(200),
    idretur BIGINT NOT NULL,
    iddetail_penerimaan BIGINT NOT NULL,
    PRIMARY KEY (iddetail_retur),
    CONSTRAINT fk_detretur_retur FOREIGN KEY (idretur) REFERENCES retur(idretur),
    CONSTRAINT fk_detretur_detpenerimaan FOREIGN KEY (iddetail_penerimaan) REFERENCES detail_penerimaan(iddetail_penerimaan)
);

-- 6. TRANSAKSI PENJUALAN

CREATE TABLE penjualan (
    idpenjualan INT NOT NULL AUTO_INCREMENT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    subtotal_nilai INT NOT NULL DEFAULT 0,
    ppn INT NOT NULL DEFAULT 0,
    total_nilai INT NOT NULL DEFAULT 0,
    iduser INT NOT NULL,
    idmargin_penjualan INT NOT NULL,
    PRIMARY KEY (idpenjualan),
    CONSTRAINT fk_penjualan_user FOREIGN KEY (iduser) REFERENCES user(iduser),
    CONSTRAINT fk_penjualan_margin FOREIGN KEY (idmargin_penjualan) REFERENCES margin_penjualan(idmargin_penjualan)
);

CREATE TABLE detail_penjualan (
    iddetail_penjualan BIGINT NOT NULL AUTO_INCREMENT,
    harga_satuan INT NOT NULL,
    jumlah INT NOT NULL,
    subtotal INT NOT NULL,
    penjualan_idpenjualan INT NOT NULL,
    idbarang INT NOT NULL,
    PRIMARY KEY (iddetail_penjualan),
    CONSTRAINT fk_detpenjualan_penjualan FOREIGN KEY (penjualan_idpenjualan) REFERENCES penjualan(idpenjualan),
    CONSTRAINT fk_detpenjualan_barang FOREIGN KEY (idbarang) REFERENCES barang(idbarang)
);

CREATE TABLE kartu_stok (
    idkartu_stok BIGINT NOT NULL AUTO_INCREMENT,
    jenis_transaksi CHAR(1) NOT NULL,
    masuk INT NOT NULL DEFAULT 0,
    keluar INT NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    idtransaksi INT,
    idbarang INT NOT NULL,
    PRIMARY KEY (idkartu_stok),
    CONSTRAINT fk_kartustok_barang FOREIGN KEY (idbarang) REFERENCES barang(idbarang)
);

-- A. INSERT - data produk Omah Kulineran

INSERT INTO satuan (nama_satuan, status) VALUES
    ('Ml', 1),
    ('Liter', 1);

INSERT INTO role (nama_role) VALUES
    ('manajer'),
    ('Admin'),
    ('Kasir');

INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES
    ('CV Rempah Nusantara',       'Y', 'A'),
    ('UD Bunga Telang Sejahtera', 'T', 'A'),
    ('Petani Jahe Makmur',        'T', 'A'),
    ('Toko Jeruk Nipis Segar',    'T', 'N');

INSERT INTO `user` (username, password, idrole) VALUES
    ('manajer',  SHA2('manajer123', 256), 1),
    ('admin1',   SHA2('admin123', 256), 2),
    ('kasir1',   SHA2('kasir123', 256), 3),
    ('kasir2',   SHA2('kasir123', 256), 3);	

INSERT INTO barang (jenis, nama, idsatuan, status, harga) VALUES
    ('A', 'Sirup Kunyit Asem Jeruk Nipis 250 ml', 1, 1,  30000),
    ('A', 'Sirup Kunyit Asem Jeruk Nipis 500 ml', 1, 1,  60000),
    ('A', 'Sirup Kunyit Asem Jeruk Nipis 1 Liter',2, 1, 120000),
    ('A', 'Sirup Bunga Telang Ungu 500 ml',       1, 1,  45000),
    ('A', 'Sirup Bunga Telang Ungu 1 Liter',      2, 1,  90000),
    ('A', 'Sirup Jeruk Nipis Serai 250 ml',       1, 1,  25000),
    ('A', 'Sirup Jeruk Nipis Serai 1 Liter',      2, 1,  90000),
    ('A', 'Sirup Jahe Merah 500 ml',              1, 1,  60000),
    ('B', 'Hampers Lebaran isi 1 Sirup 1 Liter',  2, 1, 135000);

INSERT INTO margin_penjualan (persen, status, iduser) VALUES
    (20, 1, 1),
    (25, 1, 1),
    (30, 0, 2);

-- B. SELECT (cek hasil INSERT)

SELECT * FROM satuan;
SELECT * FROM role;
SELECT * FROM vendor;
SELECT * FROM `user`;
SELECT * FROM barang;
SELECT * FROM margin_penjualan;

SELECT u.iduser, u.username, r.nama_role
FROM `user` u
JOIN role r ON r.idrole = u.idrole;

SELECT b.idbarang, b.nama, s.nama_satuan, b.harga
FROM barang b
JOIN satuan s ON s.idsatuan = b.idsatuan;

SELECT * FROM vendor WHERE status = 'A';
SELECT * FROM barang WHERE harga > 50000 ORDER BY harga DESC;

-- C. UPDATE

UPDATE barang
SET harga = 32000
WHERE idbarang = 1;

UPDATE vendor
SET status = 'A'
WHERE idvendor = 4;

UPDATE margin_penjualan
SET persen = 22.5
WHERE idmargin_penjualan = 1;

UPDATE `user`
SET idrole = 2
WHERE username = 'kasir2';

SELECT * FROM barang WHERE idbarang = 1;
SELECT * FROM vendor WHERE idvendor = 4;
SELECT * FROM margin_penjualan WHERE idmargin_penjualan = 1;
SELECT * FROM `user` WHERE username = 'kasir2';

-- D. DELETE

INSERT INTO satuan (nama_satuan, status) VALUES ('Botol', 1);
DELETE FROM satuan WHERE idsatuan = 3;

INSERT INTO margin_penjualan (persen, status, iduser) VALUES (35, 0, 3);
DELETE FROM margin_penjualan WHERE idmargin_penjualan = 4;

SELECT * FROM satuan;
SELECT * FROM margin_penjualan;