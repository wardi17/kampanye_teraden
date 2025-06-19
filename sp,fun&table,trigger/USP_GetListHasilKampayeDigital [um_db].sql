USE [um_db]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Author:      [Your Name or Team]
-- Create date: [Date]
-- Description: Get list of digital campaign results for a given year and month
-- =============================================
ALTER PROCEDURE USP_GetListHasilKampayeDigital
    @tahun INT,       -- Year filter parameter
    @bulan INT        -- Month filter parameter
AS
BEGIN
    SET NOCOUNT ON;

    -- Check and drop temporary table if it exists
    IF OBJECT_ID('tempdb..#temptess') IS NOT NULL
    BEGIN
        DROP TABLE #temptess;
    END

    -- Create temporary table to hold aggregated results
    CREATE TABLE #temptess (
        ItemNo            INT,
        TransDigitalID    CHAR(20),
        Tahun             INT,
        Bulan             INT,
        User_Input        VARCHAR(50),
        Date_Edit         DATETIME,
        total_Pemasangan  FLOAT,
        total_Views       FLOAT,
        total_follower    FLOAT
    );

    -- Calculate total pemasangan for the given month (count of Kampanye with specific monitoring type)
    DECLARE @pemasangan FLOAT;
    SET @pemasangan = (
        SELECT COUNT(*) 
        FROM Kampanye  
        WHERE JenisMonitoringID = 'JM.002' 
          AND MONTH(TanggalMulai) = @bulan
    );

    -- Insert aggregated data into temporary table.
    -- Join main campaign monitoring with detail table to sum views and followers.
    INSERT INTO #temptess
    SELECT
        a.ItemNo,
        a.TransDigitalID,
        a.Tahun,
        a.Bulan,
        a.User_Input,
        a.Date_Edit,
        @pemasangan AS total_Pemasangan,
        ISNULL(SUM(b.Views), 0) AS total_Views,
        ISNULL(SUM(b.follower), 0) AS total_follower
    FROM TrMonitoringKampanyeDigital a
    LEFT JOIN TrMonitoringKampanyeDigitalDetail b
        ON b.TransDigitalID = a.TransDigitalID
    WHERE a.Tahun = @tahun
      AND a.Bulan = @bulan
    GROUP BY
        a.ItemNo,
        a.TransDigitalID,
        a.Tahun,
        a.Bulan,
        a.User_Input,
        a.Date_Edit;

    -- Return the result set
    SELECT * FROM #temptess;

    -- Clean up temporary table (optional as it will be dropped automatically)
    DROP TABLE #temptess;
END
GO


-- Eksekusi prosedur
EXEC USP_GetListHasilKampayeDigital 2025, 6

