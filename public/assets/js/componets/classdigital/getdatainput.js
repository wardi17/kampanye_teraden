import { baseUrl } from '../config.js';

// ===============================
// GET INPUTAN MANUAL DAN  KUISONER
// ===============================
export function getInputdata() {
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

   const getTampilData =(datas)=>{
                    $.ajax({
                    url: `${baseUrl}/router/seturl`,
                    method: "POST",
                    dataType: "json",
                    data: datas,
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    headers: { 'url': 'mont/getdigital' },
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
     let html =``;
        
          html +=`<h5>Kampanye Digital</h5>`;

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
            result.forEach(element => {
                    const{id_media,nama_media,terpasang,...itemRest}=element
                html +=`<tr>
                        <td class="text-start col-1">${no++}</td>
                        <td class="text-start col-2"id="${id_media}">${nama_media}</td>
                        <td class="text-end col-2">${terpasang}</td>
                        <td><input style="width:50%; text-align; float:right; " type="number" name="view" class="form-control text-end"></td>
                        <td><input style="width:50%; text-align; float:right;"  type="number" name="follower" class="form-control text-end"></td>
                        <td class="text-start col-6"><textarea name="cataankampanye" class="form-control"></textarea></td>
                </tr>`;
            });
         html += `</tbody>
                </table>
                `;

           html +=`<div class="col-sm-11 d-flex justify-content-end mt-4">
                                   <button class="btn btn-primary me-1 mb-3" id="SubmitBtn">Simpan</button>
            </div>`         
     $("#digitalinput").empty().html(html);
  }