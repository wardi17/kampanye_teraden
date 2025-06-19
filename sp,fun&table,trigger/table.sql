USE um_db;
GO


-- Tabel Jenis Monitoring
CREATE TABLE jenis_monitoring (
    JenisMonitoringID CHAR(10) NOT NULL PRIMARY KEY,
    NamaMonitoring VARCHAR(150) NOT NULL
);


INSERT INTO jenis_monitoring (JenisMonitoringID,NamaMonitoring)values('JM.001','Manual');
INSERT INTO jenis_monitoring (JenisMonitoringID,NamaMonitoring)values('JM.002','Digital');

GO

-- Tabel media_kampanye
CREATE TABLE media_kampanye(
     id_media CHAR(10) NOT NULL PRIMARY KEY,
     Nama_Media  VARCHAR(150) NOT NULL,
     JenisMonitoringID CHAR(10) NOT NULL,
     FOREIGN KEY (JenisMonitoringID) REFERENCES jenis_monitoring(JenisMonitoringID),
)

--kategori manual
INSERT INTO media_kampanye(id_media,Nama_Media,JenisMonitoringID)values('MD.25001','Spanduk', 'JM.001');
INSERT INTO media_kampanye(id_media,Nama_Media,JenisMonitoringID)values('MD.25002','Banner', 'JM.001');
INSERT INTO media_kampanye(id_media,Nama_Media,JenisMonitoringID)values('MD.25003','Brouser', 'JM.001');
INSERT INTO media_kampanye(id_media,Nama_Media,JenisMonitoringID)values('MD.25004','Kartu nama', 'JM.001');
--and kategori manual
-- kategori digital
INSERT INTO media_kampanye(id_media,Nama_Media,JenisMonitoringID)values('MD.25005','Instagram', 'JM.002');
INSERT INTO media_kampanye(id_media,Nama_Media,JenisMonitoringID)values('MD.25006','Tiktok', 'JM.002');
--and kategori digital

GO


-- Tabel Kampanye
CREATE TABLE Kampanye (
    itemno int NOT NULL IDENTITY(1,1),
    KampanyeID CHAR(20) NOT NULL PRIMARY KEY ,
    NamaKampanye VARCHAR(100) NOT NULL,
    JenisMonitoringID CHAR(10),
    id_media CHAR(10) NOT NULL,
    Wilayah VARCHAR(100) NULL,
    lokasi VARCHAR(255) NULL,
    documen_file VARCHAR(1000) NULL,
	ket TEXT NULL,
    TanggalMulai DATETIME,
    TanggalSelesai DATETIME,
    UserInput VARCHAR(50)
    FOREIGN KEY (JenisMonitoringID) REFERENCES jenis_monitoring(JenisMonitoringID),
    FOREIGN KEY (id_media) REFERENCES media_kampanye(id_media),
);

GO

--Master_pertanya_produk
 CREATE TABLE Master_pertanyaan_produk(
    ms_pertanyaanID CHAR(10) NOT NULL PRIMARY KEY,
    Sumber_Pengetahuan VARCHAR(200) NOT NULL,
    JenisMonitoringID CHAR(10) NOT NULL,
    FOREIGN KEY (JenisMonitoringID) REFERENCES jenis_monitoring(JenisMonitoringID)
 )

 INSERT INTO Master_pertanyaan_produk(ms_pertanyaanID,Sumber_Pengetahuan,JenisMonitoringID) VALUES('MPP.25.001','Dari mana Anda mengetahui tentang produk ini?','JM.001'); 
 INSERT INTO Master_pertanyaan_produk(ms_pertanyaanID,Sumber_Pengetahuan,JenisMonitoringID) VALUES('MPP.25.002','Apa yang membuat Anda tertarik?','JM.001'); 


--Master_pertanya_produkdetail
 CREATE TABLE Master_pertanyaan_produkdetail(
    ID_detailPertanyaan CHAR(10) NOT NULL PRIMARY KEY,
    ms_pertanyaanID CHAR(10) NOT NULL,
    Alasan_Tertarik VARCHAR(200) NOT NULL
    FOREIGN KEY (ms_pertanyaanID) REFERENCES Master_pertanyaan_produk(ms_pertanyaanID)
 )

 INSERT INTO Master_pertanyaan_produkdetail(ID_detailPertanyaan,ms_pertanyaanID,Alasan_Tertarik) VALUES('PPD.25.001','MPP.25.001','Spanduk,banner,brouser,kartu nama'); 
 INSERT INTO Master_pertanyaan_produkdetail(ID_detailPertanyaan,ms_pertanyaanID,Alasan_Tertarik) VALUES('PPD.25.002','MPP.25.001','Sosial media (Instagram, Tiktok)'); 
 INSERT INTO Master_pertanyaan_produkdetail(ID_detailPertanyaan,ms_pertanyaanID,Alasan_Tertarik) VALUES('PPD.25.003','MPP.25.001','Rekomendasi teman');
 INSERT INTO Master_pertanyaan_produkdetail(ID_detailPertanyaan,ms_pertanyaanID,Alasan_Tertarik) VALUES('PPD.25.004','MPP.25.001','Toko fisik'); 
 INSERT INTO Master_pertanyaan_produkdetail(ID_detailPertanyaan,ms_pertanyaanID,Alasan_Tertarik) VALUES('PPD.25.005','MPP.25.001','Lain-lain (terangkan)'); 
 
 INSERT INTO Master_pertanyaan_produkdetail(ID_detailPertanyaan,ms_pertanyaanID,Alasan_Tertarik) VALUES('PPD.25.006','MPP.25.002','Harga'); 
 INSERT INTO Master_pertanyaan_produkdetail(ID_detailPertanyaan,ms_pertanyaanID,Alasan_Tertarik) VALUES('PPD.25.007','MPP.25.002','Kualitas'); 
 INSERT INTO Master_pertanyaan_produkdetail(ID_detailPertanyaan,ms_pertanyaanID,Alasan_Tertarik) VALUES('PPD.25.008','MPP.25.002','Desain');
 INSERT INTO Master_pertanyaan_produkdetail(ID_detailPertanyaan,ms_pertanyaanID,Alasan_Tertarik) VALUES('PPD.25.009','MPP.25.002','Keunggulan produk'); 
 INSERT INTO Master_pertanyaan_produkdetail(ID_detailPertanyaan,ms_pertanyaanID,Alasan_Tertarik) VALUES('PPD.25.010','MPP.25.002','Lain-lain (terangkan)'); 


GO

--- TABEL MASTER MONITORING KAMPANYE MANUAL
DROP TABLE TrMonitoringKampanyeManual
CREATE TABLE TrMonitoringKampanyeManual (
    ItemNo INT PRIMARY KEY IDENTITY(1,1),
    MonitoringID CHAR(20) NOT NULL,           -- Digunakan sebagai foreign key di tabel lain
    Tahun INT NOT NULL,
    Bulan INT NOT NULL,
    Kesimpulan_Kampanye TEXT,
    Kesimpulan_kuesioner TEXT,
    User_Input VARCHAR(50) NOT NULL,
    Date_Input DATETIME DEFAULT GETDATE(),
    User_Edit VARCHAR(50),
    Date_Edit DATETIME DEFAULT GETDATE(),
     CONSTRAINT UQ_Tahun_Bulan UNIQUE (Tahun, Bulan)
);

/*ALTER TABLE TrMonitoringKampanyeManual
ADD CONSTRAINT UQ_Tahun_Bulan UNIQUE (Tahun, Bulan);
ALTER TABLE TrMonitoringKampanyeManual
ADD CONSTRAINT DF_TrMonitoringKampanyeManual_Date_Edit DEFAULT GETDATE() FOR Date_Edit;

*/


-- TABEL DETAIL KAMPANYE MANUAL (PER MEDIA)
DROP TABLE TrMonitoringKampanyeManualDetail
CREATE TABLE TrMonitoringKampanyeManualDetail (
    ItemNo INT PRIMARY KEY IDENTITY(1,1),
    MonitoringID CHAR(20) NOT NULL,                  -- Foreign key ke tabel induk
    id_media CHAR(10)  NOT NULL,                           -- Foreign key ke tabel media kampanye
    Pemasangan FLOAT DEFAULT 0,                         -- Misalnya jumlah pemasangan atau info pemasangan
    Catatan TEXT,
    FOREIGN KEY (id_media) REFERENCES media_kampanye(id_media)
);

ALTER TABLE TrMonitoringKampanyeManualDetail
ALTER COLUMN Pemasangan FLOAT;


ALTER TABLE TrMonitoringKampanyeManualDetail
ADD CONSTRAINT df_Pemasangan DEFAULT 0 FOR Pemasangan;



delete  from TrMonitoringKampanyeManualDetail
delete from TrMonitoringKampanyeKuesioner
delete  from TrMonitoringKampanyeManual

select *  from TrMonitoringKampanyeManual
select *  from TrMonitoringKampanyeManualDetail
select * from TrMonitoringKampanyeKuesioner

-- TABEL KUISONER KAMPANYE (INDUK)
DROP TABLE TrMonitoringKampanyeKuesioner
CREATE TABLE TrMonitoringKampanyeKuesioner (
    ItemNo INT PRIMARY KEY IDENTITY(1,1),
    MonitoringID CHAR(20) NOT NULL,
    ms_pertanyaanID CHAR(10) NOT NULL,         
    ID_detailPertanyaan  CHAR(10) NOT NULL,
    Nilai FLOAT DEFAULT 0,
    Presen FLOAT DEFAULT 0,
    qtypemasangan FLOAT DEFAULT 0,
    totalnilai FLOAT DEFAULT 0,
    totalpersen FLOAT DEFAULT 0,
   FOREIGN KEY (ID_detailPertanyaan) REFERENCES Master_pertanyaan_produkdetail(ID_detailPertanyaan)
);



--table tr_digital
CREATE TABLE TrMonitoringKampanyeDigital (
    ItemNo INT NOT NULL IDENTITY(1,1),
    TransDigitalID CHAR(20) NOT NULL PRIMARY KEY,         
    Tahun INT NOT NULL,
    Bulan INT NOT NULL,
    User_Input VARCHAR(50) NOT NULL,
    Date_Input DATETIME DEFAULT GETDATE(),
    User_Edit VARCHAR(50),
    Date_Edit DATETIME
);


CREATE TABLE TrMonitoringKampanyeDigitalDetail (
    ItemNo INT PRIMARY KEY IDENTITY(1,1),
    TransDigitalID CHAR(20) NOT NULL,
    id_media CHAR(10) NOT NULL,
    Pemasangan FLOAT DEFAULT 0,        
    Views FLOAT DEFAULT 0,
    follower FLOAT DEFAULT 0,
    Catatan TEXT,
    FOREIGN KEY (id_media) REFERENCES media_kampanye(id_media),
   FOREIGN KEY (TransDigitalID) REFERENCES TrMonitoringKampanyeDigital(TransDigitalID)
);





