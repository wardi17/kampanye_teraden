import { baseUrl } from '../config.js';
import{getTampilback} from './listdata.js';
// ===============================
// GET INPUTAN MANUAL DAN  KUISONER EDIT
// ===============================
export function getdataEdit(datas) {
        $("#listdata").empty();
        $.ajax({
                url: `${baseUrl}/router/seturl`,
                method: "POST",
                dataType: "json",
                data: datas,
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                headers: { 'url': 'mont/datadigitaledit' },
                success: function (result) {
                    settableinput(result)
              
                },
                error: function () {
                         Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Terjadi kesalahan saat Tampil inputan data."
                    });
                }
            });
}



  const settableinput=(result)=>{
       let html =`  <div class="col-md-1 mb-4">
                            <button id="goBack" type="button" class="btn btn-lg text-start"><i class="fa-solid fa-chevron-left"></i></button>
                            </div>`;
        
          html +=`<h5>Edit Kampanye Digital</h5>`;

        html +=`<table id="table_digital" class="table table-responsive">
                    <thead class="highlight">
                    <tr> 
                       <th class="text-start col-1">No</th>
                       <th class="text-start col-2">Nama Media</th>
                       <th class="text-end col-2">Pemasangan</th>
                       <th class="text-end col-2">Jumlah view</th>
                       <th class="text-end col-2">Jumlah follower</th>
                       <th class="text-start col-6">Catatan</th>
                    </tr>
                    </thead><tbody>`;

            let no =1;
            let setiddigt="";
            result.forEach(element => {
                    const{id_media,nama_media,terpasang,TransDigitalID,Catatan,Views,follower}=element
              setiddigt =TransDigitalID
                    html +=`<tr>
                        <td class="text-start col-1">${no++}</td>
                        <td class="text-start col-2"id="${id_media}">${nama_media}</td>
                        <td class="text-end col-2">${terpasang}</td>
                        <td><input value="${Views}" style="width:50%; text-align; float:right; " type="number" name="view" class="form-control text-end"></td>
                        <td><input value="${follower}"  style="width:50%; text-align; float:right;"  type="number" name="follower" class="form-control text-end"></td>
                        <td class="text-start col-6"><textarea value="${Catatan}"  name="cataankampanye" class="form-control">${Catatan}</textarea></td>
                </tr>`;
            });
         html += `</tbody>
                </table>
                `;
         html +=`<input type="hidden" id="TransDigitalID" class="form-control" value="${setiddigt}">`;
        
         html +=`<div class="col-sm-11 d-flex justify-content-end mt-4">
                                    <button class="btn btn-secondary me-1 mb-3" id="BatalBtn">Batal</button>
                                   <button class="btn btn-success me-1 mb-3" id="UpdateBtn">Update</button>
                                  <button class="btn btn-danger me-1 mb-3" id="DeleteBtn">Delete</button>
            </div>`;        
     $("#listdata").empty().html(html);
  }


  // Fungsi untuk mengambil filter tahun dan bulan
  function getFilterData() {
      return {
          tahun: $("#filter_tahun").find(":selected").val(),
          bulan: $("#filter_bulan").find(":selected").val()
      };
  }
  
  // Event handler tombol "Batal" dan "Go Back"
  $(document).on("click", "#BatalBtn, #goBack", function (event) {
      event.preventDefault();
  
      const datas = getFilterData();
  
      $("#listdata").empty();
      getTampilback(datas);
  });


