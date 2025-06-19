USE [um_db]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

ALTER PROCEDURE USP_GetListHasilKampanye
    @tahun INT,
    @bulan INT
AS
BEGIN
    -- Hapus tabel sementara jika sudah ada
    IF EXISTS (
        SELECT * 
        FROM tempdb.dbo.sysobjects 
        WHERE id = OBJECT_ID('tempdb..#temptess') 
          AND xtype = 'U'
    )
    BEGIN
        DROP TABLE #temptess
    END

    -- Buat tabel sementara
    CREATE TABLE #temptess (
        ItemNo                INT,
        MonitoringID          CHAR(20),
        Tahun                 INT,
        Bulan                 INT,
        Kesimpulan_Kampanye   VARCHAR(5000),
        Kesimpulan_kuesioner  VARCHAR(5000),
        User_Input            VARCHAR(50),
        total_kampanye        FLOAT,
        qtypemasangan         FLOAT,
		Date_Edit			 DATETIME
    )

    -- Masukkan data ke tabel sementara
    INSERT INTO #temptess
    SELECT 
        a.ItemNo,
        a.MonitoringID,
        a.Tahun,
        a.Bulan,
        CAST(a.Kesimpulan_Kampanye AS VARCHAR(5000)),
        CAST(a.Kesimpulan_kuesioner AS VARCHAR(5000)),
        a.User_Input,
        SUM(b.Pemasangan) AS total_kampanye,

        -- Subquery kuesioner
        (
            SELECT TOP 1 c.qtypemasangan 
            FROM TrMonitoringKampanyeKuesioner c 
            WHERE c.MonitoringID = a.MonitoringID
        ) AS qtypemasangan,
		a.Date_Edit
    FROM TrMonitoringKampanyeManual a
    LEFT JOIN TrMonitoringKampanyeManualDetail b
        ON b.MonitoringID = a.MonitoringID
    WHERE a.Tahun = @tahun 
      AND a.Bulan = @bulan
    GROUP BY 
        a.ItemNo,
        a.MonitoringID,
        a.Tahun,
        a.Bulan,
        CAST(a.Kesimpulan_Kampanye AS VARCHAR(5000)),
        CAST(a.Kesimpulan_kuesioner AS VARCHAR(5000)),
        a.User_Input,
		a.Date_Edit
    -- Tampilkan hasil
    SELECT * FROM #temptess
END
GO

-- Eksekusi prosedur
EXEC USP_GetListHasilKampanye 2025, 5
