USE [um_db]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

ALTER PROCEDURE USP_GetDataInputHasilKampanye 
    @tahun INT,
    @bulan INT
AS
BEGIN
    -- Hapus tabel sementara jika sudah ada
    IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE name = '#temptess' AND xtype = 'U')
    BEGIN
        DROP TABLE #temptess
    END

    IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE name = '#temptess2' AND xtype = 'U')
    BEGIN
        DROP TABLE #temptess2
    END

    -- Buat tabel sementara untuk menyimpan data kampanye
    CREATE TABLE #temptess (
        id_media         CHAR(10),
        nama_media       VARCHAR(150),
        terpasang        FLOAT,
        NamaMonitoring   VARCHAR(50)
    )

    CREATE TABLE #temptess2 (
        id_media         CHAR(10),
        nama_media       VARCHAR(150),
        terpasang        FLOAT,
        Keterangan       VARCHAR(2000),
        NamaMonitoring   VARCHAR(50),
		id_Detail		 VARCHAR(10), 
        Detail           VARCHAR(2000),
        Sumber           VARCHAR(100)
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
        a.JenisMonitoringID = 'JM.001'
    GROUP BY 
        a.id_media,
        a.Nama_Media,
        c.NamaMonitoring

    -- Tampilkan data gabungan ke tabel sementara kedua
    INSERT INTO #temptess2
    SELECT 
        id_media,
        nama_media,
        terpasang,
        '-' AS Keterangan,
        NamaMonitoring,
		NULL AS id_Detail,
        NULL AS Detail,
        'kampanye' AS Sumber
    FROM #temptess

    UNION ALL

    SELECT 
        d.ms_pertanyaanID AS id_media,
        NULL AS nama_media,
        0 AS terpasang,
        'Pertanyaan: ' + d.Sumber_Pengetahuan AS Keterangan,
        NULL AS NamaMonitoring,
		NULL AS id_Detail,
        NULL AS Detail,
        'kuesioner' AS Sumber
    FROM Master_pertanyaan_produk d

    UNION ALL

    SELECT 
        e.ms_pertanyaanID AS id_media,
        NULL AS nama_media,
        0 AS terpasang,
        '   -' AS Keterangan,
        NULL AS NamaMonitoring,
		e.ID_detailPertanyaan AS Id_Detail,
        e.Alasan_Tertarik AS Detail,
        'kuesioner' AS Sumber
    FROM Master_pertanyaan_produkdetail e

    ORDER BY 
        Sumber, id_media, Keterangan

    -- Tampilkan hasil akhir
    SELECT * FROM #temptess2
END
GO

-- Eksekusi prosedur
EXEC USP_GetDataInputHasilKampanye 2025, 5