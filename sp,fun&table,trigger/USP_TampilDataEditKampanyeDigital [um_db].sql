USE [um_db]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Author      : [Nama Anda]
-- Create date : [Tanggal]
-- Description : Menampilkan data edit kampanye digital
-- =============================================
ALTER PROCEDURE USP_TampilDataEditKampanyeDigital 
    @tahun INT,
    @bulan INT,
    @TransDigitalID VARCHAR(20)
AS
BEGIN
    -- ==========================
    -- Cek dan hapus tabel sementara jika ada
    -- ==========================
    IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE name = '#temptess' AND xtype = 'U')
    BEGIN
        DROP TABLE #temptess
    END

	IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE name = '#temptess2' AND xtype = 'U')
    BEGIN
        DROP TABLE #temptess2
    END
    -- ==========================
    -- Buat tabel sementara untuk data kampanye
    -- ==========================
    CREATE TABLE #temptess (
        id_media        CHAR(10),
        nama_media      VARCHAR(150),
        terpasang       FLOAT,
        NamaMonitoring  VARCHAR(50)
    )

	 CREATE TABLE #temptess2 (
        id_media        CHAR(10),
        nama_media      VARCHAR(150),
        NamaMonitoring  VARCHAR(50),
		terpasang       FLOAT,
		Views			FLOAT,
		follower		FLOAT,
		Catatan			VARCHAR(5000),
		TransDigitalID  VARCHAR(20)
    )
    -- ==========================
    -- Masukkan data ke tabel sementara
    -- ==========================
    INSERT INTO #temptess
    SELECT 
        a.id_media,
        a.Nama_Media,
        COUNT(b.id_media) AS terpasang,
        c.NamaMonitoring
    FROM 
        media_kampanye a
    LEFT JOIN 
        Kampanye b ON 
            b.id_media = a.id_media AND
            DATEPART(YEAR, b.TanggalMulai) = @tahun AND
            DATEPART(MONTH, b.TanggalMulai) = @bulan
    LEFT JOIN  
        jenis_monitoring c ON 
            c.JenisMonitoringID = a.JenisMonitoringID
    WHERE 
        a.JenisMonitoringID = 'JM.002'
    GROUP BY 
        a.id_media,
        a.Nama_Media,
        c.NamaMonitoring

    -- ==========================
    -- Tampilkan hasil akhir
    -- ==========================
	INSERT INTO #temptess2
    SELECT 
        a.id_media,
        a.nama_media,
        a.NamaMonitoring,
        a.terpasang,
        b.Views,
        b.follower,
        b.Catatan,
        c.TransDigitalID
    FROM 
        #temptess a
    LEFT JOIN 
        TrMonitoringKampanyeDigitalDetail b ON b.id_media = a.id_media
    LEFT JOIN 
        TrMonitoringKampanyeDigital c ON c.TransDigitalID = b.TransDigitalID
    WHERE 
        c.TransDigitalID = @TransDigitalID AND 
        c.Tahun = @tahun AND 
        c.Bulan = @bulan

	
	BEGIN
	SELECT * FROM #temptess2
	END
END
GO

EXEC USP_TampilDataEditKampanyeDigital '2025','6','DIG.25.0001'
