import { baseUrl } from '../config.js';
import {getdataEdit} from './getdatainputedit.js';
import {conversionMonth} from '../setnamemonth.js';
// ===============================
// GET DATA LIST HASIL INPUTAN
// ===============================
export function getDatalist() {
    const style = document.createElement('style');
    style.textContent = `
        /* Untuk Chrome, Safari, Edge, dan Opera */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Untuk Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    .error {
     color :red;
    }

        #thead{
            background-color:#E7CEA6 !important;
            /* font-size: 8px;
            font-weight: 100 !important; */
            /*color :#000000 !important;*/
        }
        .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
            background-color: #F3FEB8;
            }

            /* .table-striped{
            background-color:#E9F391FF !important;
            } */
            .dataTables_filter{
                padding-bottom: 20px !important;
            }

        #frompacking{
                width:100%;
                height: 2% !important;
            margin: 0 auto;
        }
    `;
    document.head.appendChild(style);


    $("#Createdata").on("click",function(event){
        event.preventDefault();
          const tahun = $("#filter_tahun").find(":selected").val();
        const bulan = $("#filter_bulan").find(":selected").val();

        const datas ={
            "tahun" :tahun,
            "bulan" :bulan
        }

       
        getTampilData(datas);
    })
  



}
    export const getTampilback =(datas)=>{
        getTampilData(datas);
    }

     const getTampilData =(datas)=>{
            $.ajax({
            url: `${baseUrl}/router/seturl`,
            method: "POST",
            dataType: "json",
            data: datas,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            headers: { 'url': 'mont/listdatadigital' },
            success: function (result) {
                 settablelist(result)
           
          
            },
            error: function () {
            // alert("Gagal mengambil data media.");
            }
        });


    }


    const  settablelist=(result)=>{
        let datatabel = `
          <table id="tabel1" class="table table-striped table-hover" style="width:100%">                    
            <thead id="thead" class="thead">
              <tr>
                <th>No</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th class="text-end">Bayak Pemasangan</th>
                <th class="text-end">Bayak Views</th>
                <th class="text-end">Bayak Follower</th>
                <th class="text-center">Tanggal Update</th>
                 <th>UserID</th>
                <th>Edit</th>
              </tr>
            </thead>
            <tbody>
        `;

        let no =1;

       $.each( result,function(a,item){
        const {TransDigitalID,Tahun,Bulan,total_Pemasangan,total_Views,total_follower,User_Input,tanggal_update}=item;
        const nameamount =conversionMonth(Bulan);
         datatabel +=`<tr>
                        <td>${no++}</td>
                        <td>${Tahun}</td>
                        <td>${nameamount}</td>
                        <td  class="text-end">${total_Pemasangan}</td>
                        <td  class="text-end">${total_Views}</td>
                        <td class="text-end">${total_follower}</td>
                        <td class="text-center">${tanggal_update}</td>
                        <td>${User_Input}</td>
                        <td><button type="button" data-id='${TransDigitalID}' data-tahun='${Tahun}' data-bulan='${Bulan}' class="btn btn-info"  title="Edit" id="editdata"><i class="fa-solid fa-file-pen"></i></button> </td>

                 </tr>`
       })
 datatabel += `
            </tbody>
          </table>
        `;

        $("#listdata").empty().html(datatabel);
        Tampildatatabel();
    }

      //edit data
      $(document).on("click","#editdata",function(e){
        e.preventDefault();
        const TransDigitalID = $(this).data('id');
        const Tahun        = $(this).data('tahun');
        const Bulan        = $(this).data('bulan');
        const datas ={
            "TransDigitalID":TransDigitalID,
            "Tahun":Tahun,
            "Bulan":Bulan
        }
            getdataEdit(datas);
      })
      //and btn edit


        function  Tampildatatabel(){

          const id = "#tabel1";
          $(id).DataTable({
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



 
     
 
   
    
