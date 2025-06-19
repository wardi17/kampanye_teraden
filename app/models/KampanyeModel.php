<?php
class KampanyeModel extends Models{
     private $table_ktg = "[um_db].[dbo].jenis_monitoring";
     private $table_mk = "[um_db].[dbo].media_kampanye";
     private $table_kpy ="[um_db].[dbo].Kampanye";


    public function getkategori(){
            $query ="SELECT JenisMonitoringID,NamaMonitoring FROM $this->table_ktg ORDER BY JenisMonitoringID ASC";
            $result2 = $this->db->baca_sql($query);
            $datas = [];
            while(odbc_fetch_row($result2)){
            $datas[] = array(
              "id"		=>rtrim(odbc_result($result2,'JenisMonitoringID')),
              "name"	=>rtrim(odbc_result($result2,'NamaMonitoring')),
            
            );
                
                
                }
          
      
    
        return $datas;
    }


    public function getmedia($post){
            $id = $this->test_input($post["kategori"]);
            $query="SELECT id_media,Nama_Media FROM  $this->table_mk WHERE JenisMonitoringID ='".$id."'";
           $result2 = $this->db->baca_sql($query);
            $datas = [];
            while(odbc_fetch_row($result2)){
            $datas[] = array(
              "id"      =>rtrim(odbc_result($result2,'id_media')),
              "name"	=>rtrim(odbc_result($result2,'Nama_Media')),
            
            );
                
                
                }
          
      
    
        return $datas;
    }



    public function SaveDataManual($post){
      

        $datapost = $post["datas"];
   

       if(!empty($_FILES)){
          $files = $_FILES['files'];
             $total = count($files['name']);
            for ($i = 0; $i < $total; $i++) {
                $file_name = $files['name'][$i];
                $file_tmp = $files['tmp_name'][$i];
                $file_size = $files['size'][$i];
                $file_error = $files['error'][$i];
                $fileType = $files['type'][$i];
            
                    
                    if ($file_error !== UPLOAD_ERR_OK) {
                        echo "Error uploading $file_name. Error code: $file_error<br>";
                        continue;
                    }
                    $new_nama = $file_name;
                    $upload_dir = '../public/uploads_attachfile/';
                    $destination = $upload_dir . $new_nama;
                    if (move_uploaded_file($file_tmp, $destination)) {
                      $nama_atter = $new_nama;
                  }
                }
        }


       if($nama_atter !==""){
          return $this->SimpanDatapost($datapost,$nama_atter);
            
        }

    }


        private function SimpanDatapost($datapost, $documen_file)
      {
          $UserInput = isset($_SESSION['username']) ? $this->test_input($_SESSION['username']) : 'unknown';
          $arraydata = json_decode($datapost, true);

          // Pastikan semua data ada, beri nilai default jika tidak
          $id_kamp        = $this->getIdMaster();
          $kategori       = isset($arraydata["kategori"]) ? $this->test_input($arraydata["kategori"]) : '';
          $media          = isset($arraydata["media"]) ? $this->test_input($arraydata["media"]) : '';
          $namakampanye   = isset($arraydata["namakampanye"]) ? $this->test_input($arraydata["namakampanye"]) : '';
          $wilayah        = isset($arraydata["wilayah"]) ? $this->test_input($arraydata["wilayah"]) : '';
          $lokasi         = isset($arraydata["lokasi"]) ? $this->test_input($arraydata["lokasi"]) : '';
          $ket            = isset($arraydata["ket"]) ? $this->test_input($arraydata["ket"]) : '';
          $tanggal        = isset($arraydata["tanggal"]) ? $this->test_input($arraydata["tanggal"]) : '';


          // Pastikan semua data sudah di-escape
          $query = "
              INSERT INTO {$this->table_kpy} 
              (KampanyeID, NamaKampanye, JenisMonitoringID, id_media, Wilayah, lokasi, documen_file, TanggalMulai, UserInput, ket)
              VALUES (
                  '{$id_kamp}', 
                  '{$namakampanye}', 
                  '{$kategori}', 
                  '{$media}', 
                  '{$wilayah}', 
                  '{$lokasi}', 
                  '{$documen_file}', 
                  '{$tanggal}', 
                  '{$UserInput}', 
                  '{$ket}'
              )
          ";

          $cek = 0;
          $result = $this->db->baca_sql($query);

          if (!$result) {
              $cek++;
          }

          if ($cek === 0) {
              $status = [
                  'nilai' => 1,
                  'error' => 'Data Berhasil Di Simpan'
              ];
          } else {
              $status = [
                  'nilai' => 0,
                  'error' => 'Data Gagal Di Simpan'
              ];
          }

          return $status;
      }



    private function getIdMaster(){
		$query = "SELECT TOP 1 	KampanyeID  FROM $this->table_kpy  ORDER BY itemno DESC";
   
		$sql =$this->db->baca_sql($query);
		$id_master= rtrim(odbc_result($sql,'KampanyeID'));
  

		 $th = substr(date("Y"),2,2);
		 $str_star ="KPY.".$th;
		 $codedefalut ="0001";
		 
		if($id_master !==""){
			$lastNumber = (int)substr($id_master, -4);
        
			$newNumber = str_pad($lastNumber + 1, 4, "0", STR_PAD_LEFT);
		}else{
		  $newNumber=$codedefalut;
		}

		$newCode = $str_star.".".$newNumber;
	
		return $newCode;

	}



    public function SaveDataDigital($post){
       $UserInput = isset($_SESSION['username']) ? $this->test_input($_SESSION['username']) : 'unknown';
          $arraydata = json_decode($post["datas"] , true);

       

          // Pastikan semua data ada, beri nilai default jika tidak
          $id_kamp        = $this->getIdMaster();
          $kategori       = isset($arraydata["kategori"]) ? $this->test_input($arraydata["kategori"]) : '';
          $media          = isset($arraydata["media"]) ? $this->test_input($arraydata["media"]) : '';
          $namakampanye   = isset($arraydata["namakampanye"]) ? $this->test_input($arraydata["namakampanye"]) : '';
          $wilayah        = isset($arraydata["wilayah"]) ? $this->test_input($arraydata["wilayah"]) : '';
          $link         = isset($arraydata["link"]) ? $this->test_input($arraydata["link"]) : '';
          $ket            = isset($arraydata["ket"]) ? $this->test_input($arraydata["ket"]) : '';
          $tanggal        = isset($arraydata["tanggal"]) ? $this->test_input($arraydata["tanggal"]) : '';


          // Pastikan semua data sudah di-escape
          $query = "
              INSERT INTO {$this->table_kpy} 
              (KampanyeID, NamaKampanye, JenisMonitoringID, id_media, Wilayah, documen_file, TanggalMulai, UserInput, ket)
              VALUES (
                  '{$id_kamp}', 
                  '{$namakampanye}', 
                  '{$kategori}', 
                  '{$media}', 
                  '{$wilayah}', 
                  '{$link}', 
                  '{$tanggal}', 
                  '{$UserInput}', 
                  '{$ket}'
              )
          ";

          $cek = 0;
          $result = $this->db->baca_sql($query);

          if (!$result) {
              $cek++;
          }

          if ($cek === 0) {
              $status = [
                  'nilai' => 1,
                  'error' => 'Data Berhasil Di Simpan'
              ];
          } else {
              $status = [
                  'nilai' => 0,
                  'error' => 'Data Gagal Di Simpan'
              ];
          }

          return $status;
    }



    public function Tampildata(){

      $query="select a.TanggalMulai,a.KampanyeID,a.NamaKampanye,a.JenisMonitoringID,a.documen_file,a.ket,a.lokasi,
            b.NamaMonitoring,a.id_media,c.Nama_Media
              from Kampanye as a
              LEFT JOIN jenis_monitoring as b
              ON  b.JenisMonitoringID=a.JenisMonitoringID
              LEFT JOIN media_kampanye as c
              ON  c.id_media=a.id_media";
          $result2 = $this->db->baca_sql($query);
            $datas = [];
            while(odbc_fetch_row($result2)){
            $datas[] = array(
               "tanggal"		=>date('Y-m-d',strtotime(rtrim(odbc_result($result2,'TanggalMulai')))),
               "KampanyeID"     =>rtrim(odbc_result($result2,'KampanyeID')),
               "name"	        =>rtrim(odbc_result($result2,'NamaKampanye')),
               "kategori"	    =>rtrim(odbc_result($result2,'NamaMonitoring')),
               "idkategori"	    =>rtrim(odbc_result($result2,'JenisMonitoringID')),
               "media"	        =>rtrim(odbc_result($result2,'Nama_Media')),
               "id_media"	    =>rtrim(odbc_result($result2,'id_media')),
               "documen_file"	=>rtrim(odbc_result($result2,'documen_file')),
               "ket"	        =>rtrim(odbc_result($result2,'ket')),
               "lokasi"	        =>rtrim(odbc_result($result2,'lokasi')),

              
                
            
            );
                
                
                }
          
      
        //$this->consol_war($datas);
        return $datas;
    }



    public function DeleteData($post){
      

       $documen_file = $post["documen_file"];
       $KampanyeID   = $this->test_input($post["KampanyeID"]);

        $upload_dir = '../public/uploads_attachfile/';
        $file_path = $upload_dir . $documen_file;

        if (file_exists($file_path)) {
            unlink($file_path);
            }
        
            $query ="DELETE FROM  $this->table_kpy WHERE KampanyeID='".$KampanyeID."'";

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



    public function UpdateDataManual($post)
    {
        $datapost = json_decode($post["datas"], true);
        $documen_file = $datapost["documen_file"];
        $upload_dir = '../public/uploads_attachfile/';
        $nama_atter = '';

        // Ambil input
        $media         = isset($datapost["media"]) ? $this->test_input($datapost["media"]) : '';
        $namakampanye  = isset($datapost["namakampanye"]) ? $this->test_input($datapost["namakampanye"]) : '';
        $wilayah       = isset($datapost["wilayah"]) ? $this->test_input($datapost["wilayah"]) : '';
        $lokasi        = isset($datapost["lokasi"]) ? $this->test_input($datapost["lokasi"]) : '';
        $ket           = isset($datapost["ket"]) ? $this->test_input($datapost["ket"]) : '';
        $KampanyeID    = $datapost["KampanyeID"];

        if (!empty($_FILES)) {
            // Hapus file lama
            $file_path = $upload_dir . $documen_file;
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $files = $_FILES['files'];
            $total = count($files['name']);

            for ($i = 0; $i < $total; $i++) {
                $file_name  = $files['name'][$i];
                $file_tmp   = $files['tmp_name'][$i];
                $file_size  = $files['size'][$i];
                $file_error = $files['error'][$i];
                $file_type  = $files['type'][$i];

                if ($file_error !== UPLOAD_ERR_OK) {
                    echo "Error uploading $file_name. Error code: $file_error<br>";
                    continue;
                }

                $new_nama = $file_name;
                $destination = $upload_dir . $new_nama;

                if (move_uploaded_file($file_tmp, $destination)) {
                    $nama_atter = $new_nama;
                }
            }

            // Update dengan file baru
            $query = "
                UPDATE $this->table_kpy 
                SET NamaKampanye = '$namakampanye', 
                    id_media = '$media', 
                    wilayah = '$wilayah',
                    documen_file = '$nama_atter', 
                    ket = '$ket', 
                    lokasi = '$lokasi' 
                WHERE KampanyeID = '$KampanyeID'
            ";
        } else {
            // Update tanpa file
            $query = "
                UPDATE $this->table_kpy 
                SET NamaKampanye = '$namakampanye', 
                    id_media = '$media', 
                    wilayah = '$wilayah',
                    ket = '$ket', 
                    lokasi = '$lokasi' 
                WHERE KampanyeID = '$KampanyeID'
            ";
        }

        $result = $this->db->baca_sql($query);
        $status = [];

        if ($result) {
            $status['nilai'] = 1;
            $status['error'] = "Data Berhasil DiUpdated";
        } else {
            $status['nilai'] = 0;
            $status['error'] = "Data Gagal DiUpdated";
        }

        return $status;
    }


      public function UpdateDataDigital($post)
    {
        $datapost = json_decode($post["datas"], true);
   

        // Ambil input
        $media         = isset($datapost["media"]) ? $this->test_input($datapost["media"]) : '';
        $namakampanye  = isset($datapost["namakampanye"]) ? $this->test_input($datapost["namakampanye"]) : '';
        $wilayah       = isset($datapost["wilayah"]) ? $this->test_input($datapost["wilayah"]) : '';
        $link        = isset($datapost["link"]) ? $this->test_input($datapost["link"]) : '';
        $ket           = isset($datapost["ket"]) ? $this->test_input($datapost["ket"]) : '';
        $KampanyeID    = $datapost["KampanyeID"];

     
            // Update tanpa file
            $query = "
                UPDATE $this->table_kpy 
                SET NamaKampanye = '$namakampanye', 
                    id_media = '$media', 
                    wilayah = '$wilayah',
                    ket = '$ket', 
                    documen_file = '$link' 
                WHERE KampanyeID = '$KampanyeID'
            ";
        
        $result = $this->db->baca_sql($query);
        $status = [];

        if ($result) {
            $status['nilai'] = 1;
            $status['error'] = "Data Berhasil DiUpdated";
        } else {
            $status['nilai'] = 0;
            $status['error'] = "Data Gagal DiUpdated";
        }

        return $status;
    }


    public function GetlistToday(){
           $tgl_sekarang=date('Y-m-d');

           $query="select a.TanggalMulai,a.KampanyeID,a.NamaKampanye,a.JenisMonitoringID,a.documen_file,a.ket,a.lokasi,
            b.NamaMonitoring,a.id_media,c.Nama_Media
              from Kampanye as a
              LEFT JOIN jenis_monitoring as b
              ON  b.JenisMonitoringID=a.JenisMonitoringID
              LEFT JOIN media_kampanye as c
              ON  c.id_media=a.id_media where TanggalMulai='".$tgl_sekarang."'";

            $result2 = $this->db->baca_sql($query);
            $datas = [];
            while(odbc_fetch_row($result2)){
            $datas[] = array(
               "tanggal"		=>date('Y-m-d',strtotime(rtrim(odbc_result($result2,'TanggalMulai')))),
               "KampanyeID"     =>rtrim(odbc_result($result2,'KampanyeID')),
               "name"	        =>rtrim(odbc_result($result2,'NamaKampanye')),
               "kategori"	    =>rtrim(odbc_result($result2,'NamaMonitoring')),
               "idkategori"	    =>rtrim(odbc_result($result2,'JenisMonitoringID')),
               "media"	        =>rtrim(odbc_result($result2,'Nama_Media')),
               "id_media"	    =>rtrim(odbc_result($result2,'id_media')),
               "documen_file"	=>rtrim(odbc_result($result2,'documen_file')),
               "ket"	        =>rtrim(odbc_result($result2,'ket')),
               "lokasi"	        =>rtrim(odbc_result($result2,'lokasi')),

              
                
            
            );
                
                
                }
          
      
        //$this->consol_war($datas);
        return $datas;
    }
}