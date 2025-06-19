USE [um_db]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

ALTER PROCEDURE USP_TampilDataEditHasilKampanye 
    @tahun INT,
    @bulan INT,
    @MonitoringID VARCHAR(20)
AS
BEGIN
    -- Hapus tabel sementara jika sudah ada
    IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE name = '#temptess' AND xtype = 'U')
        DROP TABLE #temptess

    IF EXISTS (SELECT * FROM tempdb..sysobjects WHERE name = '#temptess2' AND xtype = 'U')
        DROP TABLE #temptess2

    -- Buat tabel sementara untuk menyimpan data kampanye
    CREATE TABLE #temptess (
        id_media         CHAR(10),
        nama_media       VARCHAR(150),
        terpasang        FLOAT,
        NamaMonitoring   VARCHAR(50)
    )

    CREATE TABLE #temptess2 (
        id_media             CHAR(10),
        nama_media           VARCHAR(150),
        terpasang            FLOAT,
        Catatan              VARCHAR(5000),
        Kesimpulan_Kampanye  VARCHAR(5000),
        Keterangan           VARCHAR(2000),
        NamaMonitoring       VARCHAR(50),
        id_Detail            VARCHAR(10), 
        Detail               VARCHAR(2000),
		Kesimpulan_kuesioner VARCHAR(5000),
		Nilai				FLOAT,
		Presen				FLOAT,
		qtypemasangan		FLOAT,
        Sumber              VARCHAR(100)
    )

    -- Isi data kampanye ke tabel sementara
    INSERT INTO #temptess
    SELECT 
        a.id_media,
        a.Nama_Media,
        COUNT(b.id_media) AS terpasang,
        c.NamaMonitoring
    FROM media_kampanye AS a
    LEFT JOIN Kampanye AS b 
        ON b.id_media = a.id_media 
        AND DATEPART(YEAR, b.TanggalMulai) = @tahun 
        AND DATEPART(MONTH, b.TanggalMulai) = @bulan
    LEFT JOIN jenis_monitoring AS c 
        ON c.JenisMonitoringID = a.JenisMonitoringID
    WHERE a.JenisMonitoringID = 'JM.001'
    GROUP BY a.id_media, a.Nama_Media, c.NamaMonitoring

    -- Tampilkan data gabungan ke tabel sementara kedua
    INSERT INTO #temptess2
    SELECT 
        a.id_media,
        a.nama_media,
        ISNULL(b.Pemasangan, 0),
        b.Catatan,
        c.Kesimpulan_Kampanye,
        '-' AS Keterangan,
        a.NamaMonitoring,
        NULL AS id_Detail,
        NULL AS Detail,
		NULL AS Kesimpulan_kuesioner,
		0 AS Nilai,
		0 AS Presen,
		0 AS qtypemasangan,
        'kampanye' AS Sumber
    FROM #temptess AS a
    LEFT JOIN TrMonitoringKampanyeManualDetail AS b
        ON b.id_media = a.id_media AND b.MonitoringID = @MonitoringID
    LEFT JOIN TrMonitoringKampanyeManual AS c
        ON c.MonitoringID = b.MonitoringID
    WHERE c.Tahun = @tahun AND c.Bulan = @bulan

    UNION ALL

    SELECT 
        d.ms_pertanyaanID AS id_media,
        NULL AS nama_media,
        0 AS terpasang,
        NULL AS Catatan,
        NULL AS Kesimpulan_Kampanye,
        'Pertanyaan: ' + d.Sumber_Pengetahuan AS Keterangan,
        NULL AS NamaMonitoring,
        NULL AS id_Detail,
        NULL AS Detail,
		NULL AS Kesimpulan_kuesioner,
		0 AS Nilai,
		0 AS Presen,
		0 AS qtypemasangan,
        'kuesioner' AS Sumber
    FROM Master_pertanyaan_produk d

    UNION ALL

    SELECT 
        e.ms_pertanyaanID AS id_media,
        NULL AS nama_media,
        0 AS terpasang,
        '-' AS Catatan,
        '-' AS Kesimpulan_Kampanye,
        '-' AS Keterangan,
        NULL AS NamaMonitoring,
        e.ID_detailPertanyaan AS id_Detail,
        e.Alasan_Tertarik AS Detail,
		c.Kesimpulan_kuesioner,
		f.Nilai AS Nilai,
		f.Presen AS Presen,
		f.qtypemasangan AS qtypemasangan,
        'kuesioner' AS Sumber
    FROM Master_pertanyaan_produkdetail e
	LEFT JOIN TrMonitoringKampanyeKuesioner f
	ON f.ID_detailPertanyaan = e.ID_detailPertanyaan  AND f.ms_pertanyaanID =  e.ms_pertanyaanID
	AND f.MonitoringID=@MonitoringID
	 LEFT JOIN TrMonitoringKampanyeManual AS c
        ON c.MonitoringID = f.MonitoringID
    WHERE c.Tahun = @tahun AND c.Bulan = @bulan
    -- Tampilkan hasil akhir 
    SELECT * FROM #temptess2
    ORDER BY Sumber, id_media, Keterangan

	
END
GO

-- Eksekusi prosedur
EXEC USP_TampilDataEditHasilKampanye 2025, 5, 'KAP.25.0002'