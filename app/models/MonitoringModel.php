
<?php

 class MonitoringModel  extends Models{

    private $table_ms_pertanyaan ="[um_db].[dbo].Master_pertanyaan_produk";
    private $table_msTr   ="[um_db].[dbo].TrMonitoringKampanyeManual";
    private $table_trdetail ="[um_db].[dbo].TrMonitoringKampanyeManualDetail";
    private $table_trque    ="[um_db].[dbo].TrMonitoringKampanyeKuesioner";

     public function GetMonio($post){
      

        $tahun  = $this->test_input($post["tahun"]);
        $bulan  = $this->test_input($post["bulan"]);

        $query ="USP_GetDataInputHasilKampanye '".$tahun."','".$bulan."' ";

           $result2 = $this->db->baca_sql($query);
        $datas = [];
        
          while(odbc_fetch_row($result2)){
            
            $datas[] =[
                "id_media"      => rtrim(odbc_result($result2,'id_media')),
                "nama_media"    => rtrim(odbc_result($result2,'nama_media')),
                "terpasang"     =>(int)rtrim(odbc_result($result2,'terpasang')),
                "Keterangan"    => rtrim(odbc_result($result2,'Keterangan')),
                "NamaMonitoring"=> rtrim(odbc_result($result2,'NamaMonitoring')),
                "id_Detail"     => rtrim(odbc_result($result2,'id_Detail')),
                "Detail"        => rtrim(odbc_result($result2,'Detail')),
                "Sumber"        => rtrim(odbc_result($result2,'Sumber')),

           
            ];
          }
          

          // Grouping berdasarkan Sumber
        $grouped = [];

        foreach ($datas as $row) {
            $Sumber = $row['Sumber'];
            if (!isset($grouped[$Sumber])) {
                $grouped[$Sumber] = [];
            }
            $grouped[$Sumber][] = $row;
        }
      
        //$this->consol_war($grouped);
        return $grouped;
     }

     public function ValidasiSave($post){
          $tahun          = $this->test_input($post["tahun"]);
          $bulan          = $this->test_input($post["bulan"]);
       // Gunakan parameterisasi untuk menghindari SQL Injection
   // Susun query aman
    $query = "SELECT COUNT(*) as count FROM {$this->table_msTr} WHERE Tahun = '{$tahun}' AND Bulan = '{$bulan}'";
    // Gunakan prepared statement jika `baca_sql` mendukung
    $sql = $this->db->baca_sql($query);
    $count = odbc_result($sql, "count");
        return [
            "duplicate" => ((int)$count > 0)
        ];
     }

    public function saveData($post) {
     $validasi = $this->ValidasiSave($post);

    if (isset($validasi["duplicate"]) && $validasi["duplicate"] === true) {
            return [
            'nilai' => 2,
            'error' => 'duplicate'
        ];
        }
    $masterId = $this->generateMasterId();
    return $this->saveAllData($masterId, $post);
   
}

private function saveAllData($masterId, $post) {
    $tahun          = $this->test_input($post["tahun"]);
    $bulan          = $this->test_input($post["bulan"]);
    $dataKampanye   = $post["datakampanye"];
    $dataKuesioner  = $post["dataquesioner"];
    $kesimpulanKamp = $dataKampanye["kesimpulan"];
    $kesimpulanQuiz = $dataKuesioner["kesimpulanQuisoner"];

    $headerSaved = $this->saveHeader($masterId, $tahun, $bulan, $kesimpulanKamp, $kesimpulanQuiz);
 
    if ($headerSaved === 1) {
        $kampanyeSaved = $this->saveDetailKampanye($masterId, $dataKampanye);
         
        if ($kampanyeSaved === 1) {
            return $this->saveDetailKuesioner($masterId, $dataKuesioner);
        } else {
            return $this->deleteHeader($masterId);
        }
    } else {
        return [
            'nilai' => 0,
            'error' => 'Gagal Simpan Data'
        ];
    }
}

private function saveDetailKuesioner($monitoringId, $dataKuesioner) {
    $success  = true;
    $kuis1    = $dataKuesioner["dataHasilPertanyaan1"];
    $kuis2    = $dataKuesioner["dataHasilPertanyaan2"];

    $success &= $this->insertKuesionerDetail($monitoringId, $kuis1);
    $success &= $this->insertKuesionerDetail($monitoringId, $kuis2);

    
    if ($success) {
        return [
            'nilai' => 1,
            'error' => 'Berhasil Simpan Data'
        ];
    } else {
        $this->deleteHeaderAndDetails($monitoringId);
        return [
            'nilai' => 0,
            'error' => 'Gagal Simpan Data'
        ];
    }
}

private function deleteHeaderAndDetails($monitoringId) {
    $query = "
        DELETE FROM $this->table_trdetail WHERE MonitoringID = '$monitoringId';
        DELETE FROM $this->table_msTr WHERE MonitoringID = '$monitoringId';
    ";
    $this->db->baca_sql($query);
}

private function insertKuesionerDetail($monitoringId, $dataKuesioner) {
    $qty       = $dataKuesioner["qtypemasangan"];
    $totalNilai= $dataKuesioner["totalnilai"];
    $totalPersen = $dataKuesioner["totalpersen"];
    $mspertanyaid = $dataKuesioner["mspertanyaid"];
    $details   = $dataKuesioner["datadetail"];

    foreach ($details as $item) {
        $idPertanyaan = $this->test_input($item["idPertanyaan"]);
        $nilai        = $this->test_input($item["nilai"]);
        $presen       = $this->test_input($item["presen"]);

        $query = "
            INSERT INTO $this->table_trque
            (MonitoringID,ms_pertanyaanID, ID_detailPertanyaan, nilai, Presen, qtypemasangan, totalnilai, totalpersen)
            VALUES ('$monitoringId','$mspertanyaid', '$idPertanyaan', '$nilai', '$presen', '$qty', '$totalNilai', '$totalPersen')
        ";

        if (!$this->db->baca_sql($query)) {
            return false;
        }
    }

    return true;
}

private function deleteHeader($masterId) {
    $query = "DELETE FROM $this->table_msTr WHERE MonitoringID = '$masterId'";
    $this->db->baca_sql($query);
    return [
        'nilai' => 0,
        'error' => 'Gagal Simpan Data'
    ];
}

private function saveDetailKampanye($masterId, $dataKampanye) {
    $details = $dataKampanye["datadetail"];
    $success = true;

    foreach ($details as $item) {
        $idMedia    = $this->test_input($item["id_media"]);
        $pemasangan = $this->test_input($item["pemasangan"]);
        $catatan    = $this->test_input($item["catatan"]);

        $query = "
            INSERT INTO $this->table_trdetail (MonitoringID, id_media, Pemasangan, Catatan)
            VALUES ('$masterId', '$idMedia', '$pemasangan', '$catatan')
        ";

        if (!$this->db->baca_sql($query)) {
            $success = false;
        }
    }

    return $success ? 1 : 0;
}

  private function saveHeader($masterId, $tahun, $bulan, $kesimpulanKamp, $kesimpulanKuis) {
      $userId = $_SESSION['username'];
      $query = "
          INSERT INTO $this->table_msTr 
          (MonitoringID, Tahun, Bulan, User_Input, Kesimpulan_Kampanye, Kesimpulan_kuesioner)
          VALUES ('$masterId', '$tahun', '$bulan', '$userId', '$kesimpulanKamp', '$kesimpulanKuis')
      ";

      return $this->db->baca_sql($query) ? 1 : 0;
  }

private function generateMasterId() {
    $query  = "SELECT TOP 1 MonitoringID FROM $this->table_msTr ORDER BY ItemNo DESC";
    $sql    = $this->db->baca_sql($query);
    $lastId = odbc_result($sql, "MonitoringID");

    $yearSuffix = substr(date("Y"), 2, 2); // Misalnya: "24"
    $prefix     = "KAP." . $yearSuffix;
    $default    = "0001";

    if (!empty($lastId)) {
        // Contoh format: KAP.24.0032
        $lastParts = explode('.', $lastId);

        if (count($lastParts) === 3) {
            $lastYear   = $lastParts[1]; // "24"
            $lastNumber = (int)$lastParts[2];

            if ($lastYear === $yearSuffix) {
                $newNumber = str_pad($lastNumber + 1, 4, "0", STR_PAD_LEFT);
            } else {
                // Tahun berbeda, reset ke 0001
                $newNumber = $default;
            }
        } else {
            // Format ID tidak sesuai, reset ke default
            $newNumber = $default;
        }
    } else {
        // Tidak ada ID terakhir, mulai dari default
        $newNumber = $default;
    }
    // Debug (matikan di produksi)

    return $prefix . "." . $newNumber;
}




    //tampil data list 
     public function ListDataManual($post){
        $tahun  = $this->test_input($post["tahun"]);
        $bulan  = $this->test_input($post["bulan"]);

       $query ="USP_GetListHasilKampanye '".$tahun."','".$bulan."' ";

        $result2 = $this->db->baca_sql($query);
            $datas = [];
            while(odbc_fetch_row($result2)){
            $datas[] = array(
               "MonitoringID"        =>rtrim(odbc_result($result2,'MonitoringID')),
               "Tahun"	             =>rtrim(odbc_result($result2,'Tahun')),
               "Bulan"	             =>rtrim(odbc_result($result2,'Bulan')),
               "Kesimpulan_Kampanye" =>rtrim(odbc_result($result2,'Kesimpulan_Kampanye')),
               "Kesimpulan_kuesioner"=>rtrim(odbc_result($result2,'Kesimpulan_kuesioner')),
               "User_Input"	         =>rtrim(odbc_result($result2,'User_Input')),
               "total_kampanye"	     =>(int)rtrim(odbc_result($result2,'total_kampanye')),
               "qtypemasangan"	     =>(int)rtrim(odbc_result($result2,'qtypemasangan')),
               "tanggal_update"	     =>date('d-m-y',strtotime(rtrim(odbc_result($result2,'Date_Edit')))),
              
                
            
            );
        }

       // $this->consol_war($datas);
        return $datas;
     }
    //tampil data list 
 

    //tampil data edit
     public function DataEdit($post){

     
        $tahun  = $this->test_input($post["Tahun"]);
        $bulan  = $this->test_input($post["Bulan"]);
        $MonitoringID  = $this->test_input($post["MonitoringID"]);

        $query ="USP_TampilDataEditHasilKampanye '".$tahun."','".$bulan."','".$MonitoringID."' ";
        //$this->consol_war($query);
           $result2 = $this->db->baca_sql($query);
        $datas = [];
        
          while(odbc_fetch_row($result2)){
            
            $datas[] =[
                "MonitoringID"  =>$MonitoringID,
                "id_media"      => rtrim(odbc_result($result2,'id_media')),
                "nama_media"    => rtrim(odbc_result($result2,'nama_media')),
                "terpasang"     =>(int)rtrim(odbc_result($result2,'terpasang')),
                "Keterangan"    => rtrim(odbc_result($result2,'Keterangan')),
                "NamaMonitoring"=> rtrim(odbc_result($result2,'NamaMonitoring')),
                "id_Detail"     => rtrim(odbc_result($result2,'id_Detail')),
                "Detail"        => rtrim(odbc_result($result2,'Detail')),
                "Sumber"        => rtrim(odbc_result($result2,'Sumber')),
                "Catatan"       => rtrim(odbc_result($result2,'Catatan')),
                "Kesimpulan_Kampanye"        => rtrim(odbc_result($result2,'Kesimpulan_Kampanye')),
                "Kesimpulan_kuesioner"       => rtrim(odbc_result($result2,'Kesimpulan_kuesioner')),
                "Nilai"	                     =>(int)rtrim(odbc_result($result2,'Nilai')),
                "Presen"	                 =>(int)rtrim(odbc_result($result2,'Presen')),
               "qtypemasangan"	     =>(int)rtrim(odbc_result($result2,'qtypemasangan')),
           
           
            ];
          }
          

          // Grouping berdasarkan Sumber
        $grouped = [];

        foreach ($datas as $row) {
            $Sumber = $row['Sumber'];
            if (!isset($grouped[$Sumber])) {
                $grouped[$Sumber] = [];
            }
            $grouped[$Sumber][] = $row;
        }
      
        //$this->consol_war($grouped);
        return $grouped;
     }

    //and data edit

    //validasi data update 
        public function ValidasiEdit($post)
            {
                $tahun    = addslashes($post["tahun"]);
                $bulan    = addslashes($post["bulan"]);
                $masterId = $this->test_input($post["MonitoringID"]);

                $query = "SELECT Tahun, Bulan FROM {$this->table_msTr} WHERE MonitoringID = '{$masterId}'";
                $sql   = $this->db->baca_sql($query);

                $Tahun = odbc_result($sql, "Tahun");
                $Bulan = odbc_result($sql, "Bulan");

                if ($tahun !== $Tahun || $bulan !== $Bulan) {
                    return [
                        'nilai' => 0,
                        'error' => 'Tahun atau Bulan tidak sama'
                    ];
                }

                return [
                    'nilai' => 1,
                    'success' => 'Validasi berhasil'
                ];
            }

    //andvalidasi data update 
    //update data edit
     public function UpdateData($post){

       $validasi = $this->ValidasiEdit($post);

        if($validasi["nilai"] === 0){
            return $validasi;
        }

        $tahun          = $this->test_input($post["tahun"]);
        $bulan          = $this->test_input($post["bulan"]);
        $masterId   = $this->test_input($post["MonitoringID"]);
    
        $dataKampanye   = $post["datakampanye"];
        $dataKuesioner  = $post["dataquesioner"];
        $kesimpulanKamp = $dataKampanye["kesimpulan"];
        $kesimpulanQuiz = $dataKuesioner["kesimpulanQuisoner"];

        $headerUpdate = $this->UpdateHeader($masterId, $tahun, $bulan, $kesimpulanKamp, $kesimpulanQuiz);
       
         if ($headerUpdate === 1) {
                $kampanyeUpdate = $this->UpdateDetailKampanye($masterId, $dataKampanye);
                if ($kampanyeUpdate === 1) {
                    return $this->UpdateDetailKuesioner($masterId, $dataKuesioner);
                } else {
                       return [
                        'nilai' => 0,
                        'error' => 'Gagal Update Data'
                    ];
                }

         }else{
                return [
                'nilai' => 0,
                'error' => 'Gagal Update Data'
            ];
        }
        
     }

    private function UpdateDetailKuesioner($masterId, $dataKuesioner){
            $success  = true;
    $kuis1    = $dataKuesioner["dataHasilPertanyaan1"];
    $kuis2    = $dataKuesioner["dataHasilPertanyaan2"];

    $success &= $this->updateKuesionerDetail($masterId, $kuis1);
    $success &= $this->updateKuesionerDetail($masterId, $kuis2);

    
        if ($success) {
            return [
                'nilai' => 1,
                'error' => 'Berhasil Update Data'
            ];
        } else {
            return [
                'nilai' => 0,
                'error' => 'Gagal Update Data'
            ];
        }
     }

     private function updateKuesionerDetail($monitoringId, $dataKuesioner) {
        $qty       = $dataKuesioner["qtypemasangan"];
        $totalNilai= $dataKuesioner["totalnilai"];
        $totalPersen = $dataKuesioner["totalpersen"];
        $mspertanyaid = $dataKuesioner["mspertanyaid"];
        $details   = $dataKuesioner["datadetail"];

            foreach ($details as $item) {
                $idPertanyaan = $this->test_input($item["idPertanyaan"]);
                $nilai        = $this->test_input($item["nilai"]);
                $presen       = $this->test_input($item["presen"]);

                $query = "
                    UPDATE  $this->table_trque  SET nilai='$nilai',Presen='$presen',
                    qtypemasangan='$qty',totalnilai='$totalNilai',totalpersen='$totalPersen'
                    WHERE MonitoringID='$monitoringId' AND ID_detailPertanyaan='$idPertanyaan' AND ms_pertanyaanID='$mspertanyaid'
                ";

                if (!$this->db->baca_sql($query)) {
                    return false;
                }
            }

            return true;
     }


     private function UpdateDetailKampanye($masterId, $dataKampanye){
         $details = $dataKampanye["datadetail"];
         $success = true;
            foreach ($details as $item) {
                $idMedia    = $this->test_input($item["id_media"]);
                $pemasangan = $this->test_input($item["pemasangan"]);
                $catatan    = $this->test_input($item["catatan"]);

                $query = "
                    UPDATE  $this->table_trdetail SET Pemasangan ='$pemasangan', Catatan='$catatan'
                    WHERE id_media='$idMedia' AND MonitoringID='".$masterId."'";

                if (!$this->db->baca_sql($query)) {
                    $success = false;
                }
            }
            return $success ? 1 : 0;
     }
     private function UpdateHeader($masterId, $tahun, $bulan, $kesimpulanKamp, $kesimpulanQuiz){
        $userId = $_SESSION['username'];
        $dateupdate = date("Y-m-d H:i:s");
            $query ="UPDATE $this->table_msTr SET  Tahun='".$tahun."', Bulan='".$bulan."', Kesimpulan_Kampanye='".$kesimpulanKamp."',Kesimpulan_kuesioner='".$kesimpulanQuiz."',
            User_Edit ='".$userId."',Date_Edit='".$dateupdate."' WHERE MonitoringID='".$masterId."' ";
       return $this->db->baca_sql($query) ? 1 : 0;
     }

    //and update data edit

    // DELETE DATA MANUAL
     public function DeleteData($post){
       

        $masterId = $this->test_input($post["MonitoringID"]);
      $query = "DELETE FROM $this->table_trque WHERE MonitoringID = '$masterId'";
      $query .= "DELETE FROM $this->table_trdetail WHERE MonitoringID = '$masterId'";
      $query .= "DELETE FROM $this->table_msTr WHERE MonitoringID = '$masterId'";
        // Eksekusi query
                $result = $this->db->baca_sql($query);
                $cek = $result ? 0 : 1;
    
                // Respon status
                if ($cek === 0) {
                    $status['nilai'] = 1;
                    $status['error'] = "Data Berhasil Delete";
                } else {
                    $status['nilai'] = 0;
                    $status['error'] = "Data Gagal Delete";
                }
    
                
                return $status;
     }

 // AND DELETE DATA MANUAL



}