<?php 
include("JadwalStreamingModel.php");
class ReportModel extends JadwalStreamingModel{



    public function ListLaporan($post){


        $tgl_from = $post["tgl_from"];
        $date_from = $this->ChangeDate($tgl_from);
     
        $tgl_to   = $post["tgl_to"];
        $date_to = $this->ChangeDate($tgl_to)." 23:59:59";

        $query="USP_LaporanJadwalStreaming '".$date_from."','".$date_to."'";

        $result2 = $this->db->baca_sql($query);
        $datas = [];
        while(odbc_fetch_row($result2)){
        $datas[] = array(
          "id_Steaming"	=>rtrim(odbc_result($result2,'id_Steaming')),
          "lokasi"		  =>rtrim(odbc_result($result2,'lokasi')),
          "host"			  =>rtrim(odbc_result($result2,'host')),
          "promosi"	    =>rtrim(odbc_result($result2,'promosi')),
          "catatan"	    =>rtrim(odbc_result($result2,'catatan')),
          "tanggal"		  =>date('Y-m-d',strtotime(rtrim(odbc_result($result2,'tanggal')))),
          "jamMulai"		=>rtrim(odbc_result($result2,'jamMulai')),
          "jamAkhir"		=>rtrim(odbc_result($result2,'jamAkhir')),
          "platform"		=>rtrim(odbc_result($result2,'platform')),
          "NamePlatfrom"=>rtrim(odbc_result($result2,'NamePlatfrom')),
          "topik"		    =>rtrim(odbc_result($result2,'topik')),
          "userid"		  =>rtrim(odbc_result($result2,'user_input')),


        );
            
            
            }
      
           // $this->consol_war($datas);

    return $datas;
   
        
      
    }


    private function ChangeDate($tanggal){
       $dateTime = DateTime::createFromFormat('d/m/Y', $tanggal);
       $formattedDate = $dateTime->format('Y-m-d');
       return $formattedDate;
    }

        public function ListLaporanHasil($post){


          $tgl_from = $post["tgl_from"];
          $date_from = $this->ChangeDate($tgl_from);
       
          $tgl_to   = $post["tgl_to"];
          $date_to = $this->ChangeDate($tgl_to)." 23:59:59";

          $query="USP_LaporanHasilStreaming '".$date_from."','".$date_to."'";

   

          $result2 = $this->db->baca_sql($query);
          $datas = [];
          while(odbc_fetch_row($result2)){
          $datas[] = array(
            "tanggal"		      =>date('d-m-Y',strtotime(rtrim(odbc_result($result2,'tanggal')))),
            "KodePlatfrom"		=>rtrim(odbc_result($result2,'KodePlatfrom')),
            "NamePlatfrom"		=>rtrim(odbc_result($result2,'NamePlatfrom')),
            "views"	          =>(int)rtrim(odbc_result($result2,'views')),
            "orders"	        =>(int)rtrim(odbc_result($result2,'orders')),
            "revenue"	        =>rtrim(odbc_result($result2,'revenue')),
  
          );
              
              
              }
        
        //$this->consol_war($datas);

      return $datas;
    
          
        
      }

}