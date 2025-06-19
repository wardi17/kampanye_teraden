<?php

include("MonitoringModel.php");
class DigitalModel  extends MonitoringModel{

    private $table_digital ="[um_db].[dbo].TrMonitoringKampanyeDigital";
   private $table_digitaldt ="[um_db].[dbo].TrMonitoringKampanyeDigitalDetail";
    public function GetDigital($post){
     
         $tahun  = $this->test_input($post["tahun"]);
        $bulan  = $this->test_input($post["bulan"]);

        $query ="USP_GetDataInputHasilDigital '".$tahun."','".$bulan."' ";

           $result2 = $this->db->baca_sql($query);
        $datas = [];
        
          while(odbc_fetch_row($result2)){
            
            $datas[] =[
                "id_media"      => rtrim(odbc_result($result2,'id_media')),
                "nama_media"    => rtrim(odbc_result($result2,'nama_media')),
                "terpasang"     =>(int)rtrim(odbc_result($result2,'terpasang')),
                "NamaMonitoring"=> rtrim(odbc_result($result2,'NamaMonitoring')),
               
           
            ];
          }

          return $datas;
    }

     public function ValidasiSaveDigital($post){
          $tahun          = $this->test_input($post["tahun"]);
          $bulan          = $this->test_input($post["bulan"]);
       // Gunakan parameterisasi untuk menghindari SQL Injection
   // Susun query aman
    $query = "SELECT COUNT(*) as count FROM {$this->table_digital} WHERE Tahun = '{$tahun}' AND Bulan = '{$bulan}'";
    // Gunakan prepared statement jika `baca_sql` mendukung
    $sql = $this->db->baca_sql($query);
    $count = odbc_result($sql, "count");
        return [
            "duplicate" => ((int)$count > 0)
        ];
     }

    public function SaveDataDigital($post){
          $validasi = $this->ValidasiSaveDigital($post);
        if (isset($validasi["duplicate"]) && $validasi["duplicate"] === true) {
            return [
            'nilai' => 2,
            'error' => 'duplicate'
        ];
        }
           $masterId = $this->getIdTransDigital();
         return $this->SaveAllDataDigital($masterId,$post);
          
    }

    private function SaveAllDataDigital($masterId,$post){
         $headerSaved = $this->SaveHeader($masterId,$post);
         if ($headerSaved === 1) {
                $saveDetail = $this->SaveDetail($masterId,$post);
                if($saveDetail === 1){
                    return $saveDetail;
                }
                return $this->DeleteHiderDigital($masterId);
            } else {
            return [
                'nilai' => 0,
                'error' => 'Gagal Simpan Data'
            ];
        }
    }

    private function DeleteHiderDigital($masterId){
           $query = "DELETE FROM $this->table_digital WHERE TransDigitalID = '$masterId'";
            $this->db->baca_sql($query);
            return [
                'nilai' => 0,
                'error' => 'Gagal Simpan Data'
            ];
    }
    private function SaveHeader($masterId,$post){
           $userid  = $_SESSION["username"];
           $tahun   = $this->test_input($post["tahun"]);
           $bulan   = $this->test_input($post["bulan"]);

           $query="INSERT INTO $this->table_digital (TransDigitalID,Tahun,Bulan,User_Input)
           VALUES('".$masterId."','".$tahun."','".$bulan."','".$userid."')";

            return $this->db->baca_sql($query) ? 1 : 0;
    }


    private function SaveDetail($masterId,$post){
      
           $datadetail = $post["datadetail"];

            $success = true;

    foreach ($datadetail as $item) {
        $idMedia    = $this->test_input($item["id_media"]);
        $pemasangan = $this->test_input($item["pemasangan"]);
        $view       = $this->test_input($item["view"]);
        $follower   = $this->test_input($item["follower"]);
        $catatan    = $this->test_input($item["catatan"]);

        $query = "
            INSERT INTO $this->table_digitaldt (TransDigitalID, id_media, Pemasangan,Views,follower, Catatan)
            VALUES ('$masterId', '$idMedia', '$pemasangan','$view','$follower', '$catatan')
        ";

        if (!$this->db->baca_sql($query)) {
            $success = false;
        }
    }

    return $success ? 1 : 0;

        
    }

    private function getIdTransDigital(){
     $query  = "SELECT TOP 1 TransDigitalID FROM $this->table_digital ORDER BY ItemNo DESC";
    $sql    = $this->db->baca_sql($query);
    $lastId = odbc_result($sql, "TransDigitalID");

    $yearSuffix = substr(date("Y"), 2, 2); // Misalnya: "24"
    $prefix     = "DIG." . $yearSuffix;
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


    public function ListDataDigital($post){
        $tahun  = $this->test_input($post["tahun"]);
        $bulan  = $this->test_input($post["bulan"]);

       $query ="USP_GetListHasilKampayeDigital '".$tahun."','".$bulan."' ";
              $result2 = $this->db->baca_sql($query);
            $datas = [];
            while(odbc_fetch_row($result2)){
            $datas[] = array(
               "TransDigitalID"        =>rtrim(odbc_result($result2,'TransDigitalID')),
               "Tahun"	             =>rtrim(odbc_result($result2,'Tahun')),
               "Bulan"	             =>rtrim(odbc_result($result2,'Bulan')),
               "User_Input"	         =>rtrim(odbc_result($result2,'User_Input')),
               "total_Pemasangan"	 =>(int)rtrim(odbc_result($result2,'total_Pemasangan')),
               "total_Views"	     =>(int)rtrim(odbc_result($result2,'total_Views')),
               "total_follower"	     =>(int)rtrim(odbc_result($result2,'total_follower')),
               "tanggal_update"	     =>date('d-m-y',strtotime(rtrim(odbc_result($result2,'Date_Edit')))),
              
                
            
            );
        }

        //$this->consol_war($datas);
        return $datas;
    }


    public function dataDigitalEdit($post){
          $tahun  = $this->test_input($post["Tahun"]);
        $bulan  = $this->test_input($post["Bulan"]);
        $TransDigitalID  = $this->test_input($post["TransDigitalID"]);

        $query ="USP_TampilDataEditKampanyeDigital '".$tahun."','".$bulan."','".$TransDigitalID."' ";
        // $this->consol_war($query);
           $result2 = $this->db->baca_sql($query);
        $datas = [];
        
          while(odbc_fetch_row($result2)){
            
            $datas[] =[
                "id_media"      => rtrim(odbc_result($result2,'id_media')),
                "nama_media"    => rtrim(odbc_result($result2,'nama_media')),
                "terpasang"     =>(int)rtrim(odbc_result($result2,'terpasang')),
                "TransDigitalID"=> rtrim(odbc_result($result2,'TransDigitalID')),
                "Catatan"       => rtrim(odbc_result($result2,'Catatan')),
                "Views"	        =>(int)rtrim(odbc_result($result2,'Views')),
                "follower"	    =>(int)rtrim(odbc_result($result2,'follower')),
         
           
           
            ];
          }
          return $datas;
    }



    //validasi data update 
        public function ValidasiEditDigital($post)
            {
                $tahun    = addslashes($post["tahun"]);
                $bulan    = addslashes($post["bulan"]);
                $masterId = $this->test_input($post["TransDigitalID"]);

                $query = "SELECT Tahun, Bulan FROM {$this->table_digital} WHERE TransDigitalID = '{$masterId}'";
                
             
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


    public function UpdateDataDigital($post){
       
       $validasi= $this->ValidasiEditDigital($post);
         if($validasi["nilai"] === 0){
            return $validasi;
        }
        return $this->UpdateDataAllDigital($post);
      

    }

     private function UpdateDataAllDigital($post){
        $masterId   = $this->test_input($post["TransDigitalID"]);
         $headupdate = $this->UpdateHeader($masterId);
         if($headupdate === 1){
             return $this->UpdateDetail($masterId,$post);
         }else{
            return [
                'nilai' => 0,
                'error' => 'Gagal Update Data'
            ];
         }
     }

    private function UpdateHeader($masterId){
         $userId = $_SESSION['username'];
        $dateupdate = date("Y-m-d H:i:s");
            $query ="UPDATE $this->table_digital SET User_Edit ='".$userId."',Date_Edit='".$dateupdate."' WHERE TransDigitalID='".$masterId."' ";
       return $this->db->baca_sql($query) ? 1 : 0;
    }

    private function UpdateDetail($masterId,$post){
            $datadetail = $post["datadetail"];

            //$this->consol_war($datadetail);
            $success = true;

            foreach($datadetail as $item){
                $idMedia    = $this->test_input($item["id_media"]);
                $pemasangan = $this->test_input($item["pemasangan"]);
                $view       = $this->test_input($item["view"]);
                $follower   = $this->test_input($item["follower"]);
                $catatan    = $this->test_input($item["catatan"]);

                $query="UPDATE $this->table_digitaldt SET Pemasangan='".$pemasangan."',Views='".$view."',follower='".$follower."',Catatan='".$catatan."'
                 WHERE TransDigitalID='".$masterId."' AND id_media='".$idMedia."'";
                  if (!$this->db->baca_sql($query)) {
                    $success = false;
                } 
            }

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


           public function DeleteDataDigital($post){

                $masterId = $this->test_input($post["TransDigitalID"]);
                $query   = "DELETE FROM {$this->table_digitaldt} WHERE TransDigitalID = '{$masterId}'";
                $query  .= "DELETE FROM {$this->table_digital} WHERE TransDigitalID = '{$masterId}'";
            
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

        
    }
