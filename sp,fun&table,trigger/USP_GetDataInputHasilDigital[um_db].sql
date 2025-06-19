USE [um_db]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

ALTER PROCEDURE USP_GetDataInputHasilDigital 
    @tahun INT,
    @bulan INT
AS
BEGIN
    -- Hapus tabel sementara jika sudah ada
    IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE name = '#temptess' AND xtype = 'U')
    BEGIN
        DROP TABLE #temptess
    END

 

    -- Buat tabel sementara untuk menyimpan data kampanye
    CREATE TABLE #temptess (
        id_media         CHAR(10),
        nama_media       VARCHAR(150),
        terpasang        FLOAT,
        NamaMonitoring   VARCHAR(50)
    )



    -- Isi data kampanye ke tabel sementara
    INSERT INTO #temptess
    SELECT 
        a.id_media,
        a.Nama_Media,
        COUNT(b.id_media) AS terpasang,
        c.NamaMonitoring
    FROM 
        media_kampanye AS a
    LEFT JOIN 
        Kampanye AS b ON b.id_media = a.id_media 
        AND DATEPART(YEAR, b.TanggalMulai) = @tahun 
        AND DATEPART(MONTH, b.TanggalMulai) = @bulan
    LEFT JOIN  
        jenis_monitoring AS c ON c.JenisMonitoringID = a.JenisMonitoringID
    WHERE 
        a.JenisMonitoringID = 'JM.002'
    GROUP BY 
        a.id_media,
        a.Nama_Media,
        c.NamaMonitoring

    SELECT * FROM #temptess
END
GO

-- Eksekusi prosedur
EXEC USP_GetDataInputHasilDigital 2025, 5