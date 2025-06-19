<?php

$userid = $data["userid"];

?>
<style>
  #thead {
    background-color: #E7CEA6 !important;
  }

  .table-hover tbody tr:hover td,
  .table-hover tbody tr:hover th {
    background-color: #F3FEB8;
  }

  .dataTables_filter {
    padding-bottom: 20px !important;
  }

  form {
    width: 100%;
    height: 2% !important;
    margin: 0 auto;
  }

  @media (max-width: 768px) {
    #filterdata .form-group {
      margin-bottom: 1rem;
    }

    #filterdata .form-group label,
    #filterdata .form-group input {
      width: 100% !important;
    }

    #filterdata button {
      width: 100%;
    }
  }

  @media (max-width: 576px) {
    .form-group label {
        font-size: 14px; /* Mengatur ukuran font label pada perangkat kecil */
    }
    .btn {
        width: 100%; /* Membuat tombol memenuhi lebar form pada perangkat kecil */
    }
}
</style>
<div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <div class="page-heading mb-3">
          <div class="page-title">
            <h6 class="text-start">Laporan Detail</h6>
          </div>
        </div>

        <div id="filterdata" class="row align-items-end">
          <input type="hidden" id="username" value="<?=$userid?>" />
        </div>
        <div class="row align-items-end">
              <!-- From -->
              <div class="col-12 col-md-4 col-lg-2 mb-2">
                  <div class="form-group">
                      <label for="tgl_from" class="form-label">From</label>
                      <input type="date" class="form-control" id="tgl_from" name="tgl_from">
                  </div>
              </div>

              <!-- To -->
              <div class="col-12 col-md-4 col-lg-2 mb-2">
                  <div class="form-group">
                      <label for="tgl_to" class="form-label">To</label>
                      <input type="date" class="form-control" id="tgl_to" name="tgl_to">
                  </div>
              </div>

              <!-- Submit Button -->
              <div class="col-12 col-md-4 col-lg-2 mb-2">
                  <div class="form-group d-flex justify-content-start">
                      <button type="submit" class="btn btn-primary" id="Createdata">Submit</button>
                  </div>
              </div>
          </div>



        <div id="tabellist"></div>
      </div>
    </div>
  </div>
</div>

  <script>
     
    $(document).ready(function(){
      gettanggal();


        $("#Createdata").on("click",function(event){
            event.preventDefault();
            let tgl_to = $("#tgl_to").val();
            let tgl_from = $("#tgl_from").val();
          
            let  userid = $("#username").val();
             let datas ={
                "tgl_from":tgl_from,
                "tgl_to"  :tgl_to,
                "userid"  :userid 
             }

             
          
            getData(datas);
        })
      
    });// batas document ready

    function  gettanggal(){
	  let currentDate = new Date();
    // Mengatur tanggal pada objek Date ke 1 untuk mendapatkan awal bulan
    currentDate.setDate(1);
    // Membuat format tanggal YYYY-MM-DD
    //let tgl_from = currentDate.toISOString().slice(0,10);
    // Menampilkan hasil
    let id_from ="tgl_from";


    let d = new Date();
      let month = d.getMonth()+1;
      let day = d.getDate();
      let  tgl_to =  d.getFullYear() +'-'+
					(month<10 ? '0' : '') + month + '-' +
				 (day<10 ? '0' : '') + day;

      let id_tgl_to ="tgl_to";
      SetTanggal(id_from,tgl_to)
      SetTanggal(id_tgl_to,tgl_to)


}
SetTanggal=(id,tanggal)=>{
  
    let setid ="#"+id;
    flatpickr(setid, {
                dateFormat: "d/m/Y", // Format yang diinginkan
                allowInput: true ,// Memungkinkan input manual
                defaultDate: new Date(tanggal)
            });
      
  }

    function  getData(datas){


      $.ajax({
          url:"<?=base_url?>/router/seturl",
                method:"POST",
                dataType: "json",
                headers:{
                  'url':'lap/listlaporan'
                },
                data:datas,
                  success:function(result){
               
                    Set_Tabel(result);
                    
                  }
      })
    }

   
    
  function Set_Tabel(result){
    
  
    let datatabel = ``;

        datatabel +=`
                    <table id="tabel1" class='table table-striped table-hover' style='width:100%'>                    
                                          <thead  id='thead'class ='thead'>
                                                    <tr>
                                                                <th>No</th>
                                                                <th>Tanggal</th>
                                                                <th>Jam</th>
                                                                <th>Lokasi</th>
                                                                <th>Platform</th>
                                                                <th>Host</th>
                                                                <th>Topik</th>
                                                                <th>Promosi</th>
                                                                <th>Catatan</th>
                                                                <th>User ID</th>
                                                                <th>View</th>
                                                               
                                                    </tr>
                                                    
                                                    </thead>
                                                    <tbody>
                                              
        `;

        let no =1;
              $.each(result,function(a,b){
                let formatTanggal = moment(b.tanggal).format("DD-MM-YYYY");
                let split_tgl = formatTanggal.split("-");
                
                let t = split_tgl[0];
                let m = split_tgl[1];
                let y = split_tgl[2];
                let sub_y = y.substr(2,2);
                let new_tgl = t+'-'+m+'-'+sub_y;

                let EntryDate = moment(b.Tanggal).format("YYYY-MM-DD");

                const {lokasi,host,promosi,catatan,tanggal,jamMulai,jamAkhir,platform,topik,userid,NamePlatfrom}=b
                datatabel +=`
                            <td>${no++}</td>
                            <td>${new_tgl}</td>
                            <td>${jamMulai} - ${jamAkhir}</td>
                             <td>${lokasi}</td>
                            <td>${NamePlatfrom}</td>
                            <td>${host}</td>
                            <td>${topik}</td>
                            <td>${promosi}</td>
                            <td>${catatan}</td>
                            <td>${userid}</td>
                            `;
                     datatabel +=`<td><button type="button" class="btn btn-primary modaldetail" id="modaldetail" 
                        data-tanggal='${tanggal}' data-jammulai='${jamMulai}' data-jamakhir ='${jamAkhir}' data-topik='${topik}'
                        data-lokasi='${lokasi}' data-plaform='${platform}' data-host='${host}' data-promosi='${promosi}'
                        data-catatan ='${catatan}'
                        ><i class="fa-brands fa-steam"></i></button></td>`;
                     
           
                            datatabel +=`</tr>`;
              });
              datatabel +=`</tbody></table>`;
              $("#tabellist").empty().html(datatabel);
              Tampildatatabel();
  }

  function  Tampildatatabel(){

    const tabel1 = "#tabel1";
    $(tabel1).DataTable({
        order: [[0, 'asc']],
          responsive: true,
          "ordering": true,
          "destroy":true,
          pageLength: 5,
          lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']],
          fixedColumns:   {
              // left: 1,
                right: 1
            },
            
        })
   }


   $(document).on("click",".modaldetail",function(event){
    event.preventDefault();
    $("#ModalDetail").modal("show");
    
          // Ambil data dari elemen yang diklik
        const tanggal   = $(this).data("tanggal");
        const jamMulai  = $(this).data("jammulai");
        const jamAkhir  = $(this).data("jamakhir");
        const lokasi    = $(this).data("lokasi");
        const platform  = $(this).data("plaform"); // Asumsi typo: 'plaform' seharusnya 'platform' jika konsisten
        const host      = $(this).data("host");
        const promosi   = $(this).data("promosi");
        const catatan   = $(this).data("catatan");
        const topik     = $(this).data("topik"); // Ditambahkan karena digunakan di bawah
 
        // Set nilai ke input form
        $("#tanggal").val(tanggal);
        // Jika ingin mengatur waktu secara khusus, gunakan fungsi berikut:
        setWaktudetail("start", jamMulai);
        setWaktudetail("end", jamAkhir);

        $("#lokasi").val(lokasi);
        $("#platform").val(platform); // Ganti selector sesuai id input form
        $("#host").val(host);
        $("#topik").val(topik);
        $("#promosi").val(promosi);
        $("#catatan").val(catatan);
   })

   function setWaktudetail(selectorPrefix, waktu) {
      console.log(selectorPrefix)
    const [jam, menit, detik] = waktu.split(":");
    $(`#${selectorPrefix}Hour`).val(jam);
    $(`#${selectorPrefix}Minute`).val(menit);
    $(`#${selectorPrefix}Second`).val(detik);
}
  </script>