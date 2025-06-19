import { baseUrl } from '../config.js';
import{getTampilback} from './listdata.js';
import{conversionMonth} from '../setnamemonth.js';
// ===============================
// GET INPUTAN MANUAL DAN  KUISONER EDIT
// ===============================
export function getdataEdit(datas) {
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
        .inputkusioner{
            width:50%;
        }

      /*  #tdkusioner2{
        text-align: right;
        }

       #tdkusioner{
        display: flex; justify-content: end; align-items: center;
        }*/
    `;
  
    document.head.appendChild(style);

        $("#listdata").empty();
        let html =`<div  id="kampanye"></div>
              <div id="kuesioner"></div>`;
        $("#listdata").html(html);
        $.ajax({
                url: `${baseUrl}/router/seturl`,
                method: "POST",
                dataType: "json",
                data: datas,
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                headers: { 'url': 'mont/dataedit' },
                success: function (result) {
                    const kampanye = result.kampanye;
                    const kuesioner = result.kuesioner;

                   
                       settableKampanye(kampanye);
                       settableKuesioner(kuesioner);
              
                },
                error: function () {
                // alert("Gagal mengambil data media.");
                }
            });
}

  const settableKampanye=(kampanye)=>{
        const tahun  =$("#filter_tahun").val();
        const bulan  =$("#filter_bulan").val();
         const nameamount =conversionMonth(bulan);
        let html =`  <div class="col-md-1 mb-1">
                            <button id="goBack" type="button" class="btn btn-lg text-start"><i class="fa-solid fa-chevron-left"></i></button>
                            </div>`;
        html +=`<div>
            <button id="Print" type="button" class="btn btn-sm btn-primary text-start">Print </button>
        </div>`;
        
          html +=`<h5>Kampanye Theradent Wilayah  Kalimanatan  Periode ${nameamount} ${tahun}   </h5>`;

        html +=`<table id="table_kampanye" class="table table-responsive">
                    <thead class="highlight">
                    <tr> 
                       <th class="text-center">No</th>
                       <th class="text-start">Nama Media</th>
                       <th class="text-center">Pemasangan</th>
                       <th class="text-start">Catatan</th>
                    </tr>
                    </thead><tbody>`;
         let no =1;
         let Kesimpulan_Kampanye="";
         let setidmot ="";
        $.each(kampanye,function(a,item){
          
                const{nama_media,terpasang,MonitoringID,...itemRest}=item
       
             setidmot =MonitoringID;
                Kesimpulan_Kampanye =itemRest.Kesimpulan_Kampanye;
                html +=`<tr>
                        <td  class="text-center">${no++}</td>
                        <td class="text-start"id="${itemRest.id_media}">${nama_media}</td>
                        <td class="text-center">${terpasang}</td>
                        <td class="text-start"><textarea name="cataankampanye" value="${itemRest.Catatan}" class="form-control">${itemRest.Catatan}</textarea></td>
                </tr>`;
        })
         html += `</tbody>
                </table>
                `;
        html +=` <div class="row col-md-12">
                <label for="kesimpulan_kampanye" class="col-sm-2 form-label">Kesimpulan</label>
                <div class="col-sm-4">
                    <textarea id="kesimpulan_kampanye" value="${Kesimpulan_Kampanye}" class="form-control" style="width:150%;">${Kesimpulan_Kampanye}</textarea>
                <span id="kesimpulan_kampanyeError" class="error"></span>
                </div>
            </div>`
        html +=`<input type="hidden" id="MonitoringID" class="form-control" value="${setidmot}">`;
         $("#kampanye").empty().html(html);
    }


    const settableKuesioner = (kuesioner) => {
    const grouped = {};

   let setqty =0;
    // Kelompokkan berdasarkan id_media
    kuesioner.forEach(item => {
          const rawQty = item.qtypemasangan;

          // Ambil hanya jika angka dan bukan 0
          if (!isNaN(rawQty) && Number(rawQty) !== 0) {
            setqty = parseFloat(rawQty);
            
            // Lakukan proses sesuai kebutuhan
        
            // Misal: push ke array, atau total jumlah
          }
        const id = item.id_media;
        if (!grouped[id]) {
            grouped[id] = {
                pertanyaan:[],
                pilihan: []
            };
        }

        if (item.Keterangan.trim().startsWith("Pertanyaan")) {
            grouped[id].pertanyaan.push({
                  id:item.id_media,
                  text:item.Keterangan.replace("Pertanyaan: ", "").trim(),
              
            });
        } else if (item.Detail) {
           
            grouped[id].pilihan.push({
                id:item.id_Detail,
                text:item.Detail.trim(),
                Nilai:item.Nilai,
                Presen:item.Presen,
                qtypemasangan:item.qtypemasangan,
                Kesimpulan_kuesioner:item.Kesimpulan_kuesioner,


            });
        }
    });

    // Awal HTML
    let html = `
       <h5 class="mt-4">Kuesioner</h5>
        <div class="row mb-3">
        <div class="col-sm-2 d-flex align-items-center">
            <label for="qtypemasangan" class="form-label mb-0">Jumlah kueisoner</label>
        </div>
        <div id="stypenilai" class="col-sm-1">
            <input class="form-control text-end" value="${setqty}" id="qtypemasangan" type="number">
            <span id="qtypemasanganError" class="error"></span>
        </div>
    `;

    // Tambahkan pertanyaan dan pilihan
    let groupidnilai='';
    let nonilai =0;
     let Kesimpulan_kuesioner ="";
    
    
    Object.values(grouped).forEach(group => {
        nonilai++;
        const group_pertanyaan = group.pertanyaan;
         let id_p='';
         let text_p='';
         group_pertanyaan.forEach(item=>{
            id_p =item.id;
            text_p = item.text;
         })

         let substrid=id_p.replace(/\./g, "");
         let idtotal ="Total_"+nonilai+"nilai";
         let idtotalpresen = "Total_"+nonilai+"persen";

   
        html += `<div class="col-md-12 pertanyaan">`;
        html += `<h5>${text_p}</h5>`;
        html +=`<input type="hidden" id="mspertanyaan${nonilai}" class="form-control" value="${id_p}">`;
        html += `<table id="${substrid}" class="table table-responsive  table_Kuesioner">
                <thead> 
                <tr>
                <th class="text-center col-1">No</th>
                 <th class="text-start col-6">Pilihan</th>
                 <th class="text-start col-2">Banyak Kuesioner</th>
                 <th class="text-start col-2">Persen %</th>
                </tr>
                </thead><tbody>
        `;
        let no =1;
       let settotalnilai =0;
       let settotalpresen =0;
        group.pilihan.forEach(pilihan => {
        let setid =pilihan.id.replace(/\./g, "");
         let idnilai = setid+"Nilai";
         let idpersen = setid+"Persen";
        settotalnilai +=pilihan.Nilai;
        settotalpresen +=pilihan.Presen;
  
         groupidnilai +=idnilai+'|'+idpersen+'|'+idtotal+',';

        Kesimpulan_kuesioner=pilihan.Kesimpulan_kuesioner;
   

            html += `<tr>
                    <td class="text-center col-1">${no++}</td>
                    <td class="col-6" id="${pilihan.id}">${pilihan.text}</td>
                    <td class="text-end"><input   value="${pilihan.Nilai}" type="number" name="nilai${nonilai}" class="inputkusioner form-control text-end ${nonilai}nilai" id="${idnilai}">
                    </td>
                    <td id="tdkusioner2" class="col-2 "><input value="${pilihan.Presen}" type="number" name="persen${nonilai}" disabled class="inputkusioner form-control text-end  ${nonilai}persen" id="${idpersen}"></td>
                    </tr>`;
          
        });
        let adspsettotal =settotalnilai.toString();
         let adspsetpresen =settotalpresen.toString();
         html +=`</tbody> <tfoot>
                <tr>
                <th colspan=2 class="text-end"> Total :</th>
                 <th class="text-end "><input id="${idtotal}" type="number" class="cltotal form-control text-end inputkusioner" disabled value="${adspsettotal}"></th>
                <th class="text-end" ><input id="${idtotalpresen}"  type="number" class="cltotal form-control text-end inputkusioner" disabled value="${adspsetpresen}"</th>
                </tr>
            `;
        html += `</tfoot></table>`;
       
    });

 
    // Tambahkan textarea kesimpulan
    html += `
        <div id="divkesimpulan" class="row col-md-12 mb-3">
            <label for="kesimpulan" class="col-sm-2 form-label">Kesimpulan</label>
            <div class="col-sm-4">
                <textarea id="kesimpulan" value="${Kesimpulan_kuesioner}"class="form-control" style="width:150%;">${Kesimpulan_kuesioner}</textarea>
                <span id="kesimpulanError" class="error"></span>
            </div>
        </div>
    `;
 html += `</div>`;
    html +=`<div id="idtombol" class="col-sm-11 d-flex justify-content-end mt-4">
                                    <button class="btn btn-secondary me-1 mb-3" id="BatalBtn">Batal</button>
                                   <button class="btn btn-success me-1 mb-3" id="UpdateBtn">Update</button>
                                  <button class="btn btn-danger me-1 mb-3" id="DeleteBtn">Delete</button>
            </div>`
    // Render ke elemen dengan ID "kuesioner
    $("#kuesioner").empty().html(html);
    let str_groupnilai =groupidnilai.slice(0,-1);
     setTotalInputNilai(str_groupnilai);
  
};





const setTotalInputNilai = (strGroupNilai) => {
    const dataIdArray = strGroupNilai.split(",");

    dataIdArray.forEach(item => {
        const [idRaw, idPersenRaw, idTotalRaw] = item.split("|");

        const selectorNilai = `#${idRaw}`;
        const selectorPersen = `#${idPersenRaw}`;
        const selectorTotal = `#${idTotalRaw}`;

      
        $(selectorNilai).on('keyup', function () {
            const qtyPemasanganVal = $("#qtypemasangan").val();
            const inputVal = $(this).val();
            const totalVal = $(selectorTotal).text();

            const currentIdNilai = $(this).attr("id");
            const currentIdPersen = currentIdNilai.replace("Nilai", "Persen");
            const selectorError = `#${currentIdNilai}Error`;
            const currentPersen = `#${currentIdPersen}`;

            // Validasi jika qty pemasangan belum diisi atau 0
            if (!qtyPemasanganVal || parseInt(qtyPemasanganVal) === 0) {
                $("#qtypemasanganError").text("Qty kuesioner harus diisi terlebih dahulu.");
                $(this).val("");                      // Kosongkan input
                $(this).prop("disabled", true);       // Nonaktifkan input
                $(".cltotal").html("");               // Kosongkan total tampilan
                $(selectorPersen).val("");            // Kosongkan input persen
                return;
            } else {
                $("#qtypemasanganError").text("");    // Bersihkan pesan error
                $(this).prop("disabled", false);      // Aktifkan input kembali
            }

            const inputFloat = parseFloat(inputVal);
            const qtyPemasanganFloat = parseFloat(qtyPemasanganVal);
            const totalFloat = parseFloat(totalVal);

            // Validasi jika input lebih besar dari qty pemasangan
            if (inputFloat > qtyPemasanganFloat) {
                $(selectorError).text("Input tidak boleh lebih besar dari Pemasangan Kuesioner");
                $(".cltotal").html("");
                $(currentPersen).val("");
                return;
            } else if (totalFloat > qtyPemasanganFloat) {
                $(selectorError).text("Input tidak boleh lebih besar dari Total");
                $(".cltotal").html("");
                return
            } else {
                $(selectorError).text(""); // Bersihkan pesan error
                setpersen(currentPersen, qtyPemasanganVal, inputVal); // Hitung persen
                 // Jika perlu, aktifkan kembali
            }
        });
    });
};


        // Tambahkan pemantauan saat qty kuesioner berubah
    $(document).on("input","#qtypemasangan", function () {
        let qty = $(this).val();
        // Jika sudah diisi benar, aktifkan semua input nilai
        if (qty && parseInt(qty) > 0) {
            $(".1nilai").prop("disabled", false);
            $(".2nilai").prop("disabled", false);
            $("#qtypemasanganError").text("");
        } else {
            $(".1nilai").prop("disabled", true);
            $(".2nilai").prop("disabled", true);
        }
    });
     
 
    //rumus set persen
     const   setpersen =(idpersen,qtypasang,inputVal)=>{

        if(qtypasang !=='' && inputVal !==''){
            let a=formatjm(inputVal);
            let b=formatjm(qtypasang);
            let rumus =(a/b)*100;
            $(idpersen).val(rumus);
        }
            
         
     }
    //

    

        $(document).on('keyup',".1nilai", function() {
                    let data = {
                        "nilai":"1nilai",
                        "presen":"1persen"
                        };
                    calculateTotal(data);
        });


          $(document).on('keyup',".2nilai", function() {
                    let data = {
                        "nilai":"2nilai",
                        "presen":"2persen"
                        };
                    calculateTotal(data);
            });



    const calculateTotal=(data)=>{
       let total = 0;
       let to_presen =0;
       let nilai =data.nilai;
       let presen =data.presen;
        let id ="."+nilai;
        //mengitung nilai
        $(id).each(function() {
          let di_value =$(this).val();
            total += formatjm(di_value) || 0; // Mengambil nilai input, jika kosong dianggap 0
        });
        let tt =total.toString();
        let id_tt = "#Total_"+nilai;
        $(id_tt).val(tt);
        //and mengithuh nilai
        //mengitung 
         let idp ="."+presen;
           
         $(idp).each(function(){
            let per_value = $(this).val();
             // Konversi string ke float (angka)
                let angka = parseFloat(per_value);
                // Jika hasilnya NaN, gunakan 0
                if (!isNaN(angka)) {
                    to_presen += angka;
                }
         })
          let id_ttps = "#Total_"+presen;
        $(id_ttps).val(to_presen);
    }


  function formatjm(angka){
    return parseFloat(angka.replace(/[^0-9.]/g, ''));
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


// event print
// Event print saat tombol #Print diklik
$(document).on("click", "#Print", function () {
  const printArea = document.getElementById("listdata").cloneNode(true);
  const originalCanvases = document.querySelectorAll("canvas");
  const clonedCanvases = printArea.querySelectorAll("canvas");
  const promises = [];

  // Ganti canvas dengan gambar agar bisa dicetak
  originalCanvases.forEach((canvas, index) => {
    const dataUrl = canvas.toDataURL("image/png");
    const img = new Image();
    img.src = dataUrl;
    img.style.maxWidth = "100%";
    img.style.marginBottom = "15px";

    const promise = new Promise((resolve) => {
      img.onload = () => {
        if (clonedCanvases[index]) {
          clonedCanvases[index].replaceWith(img);
        }
        resolve();
      };
    });

    promises.push(promise);
  });

  // Setelah semua canvas diganti gambar, buka jendela print
  Promise.all(promises).then(() => {
    const printWindow = window.open("", "", "height=1000,width=1200");

    printWindow.document.write(`
      <html>
        <head>
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Print Report</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
          <style>
            /* Global reset dan format kecil agar muat satu halaman */
            * {
              box-sizing: border-box;
            }

            input,
            textarea,
            label,
            p,
            h5 {
              font-size: 9px !important;
            }

            input {
              width: 100% !important;
              border: 1px solid #000;
            }

            textarea {
              height: 4px;
              resize: vertical;
              font-size: 10px !important;
              padding: 0;
            }

            table {
              width: 100%;
              border-collapse: collapse;
              font-size: 9px !important;
            }

            th, td {
              border: 1px solid #ccc;
              padding: 1px;
              vertical-align: middle;
              text-align: left;
              
            }

            th {
              padding: 1px;
            }

            .table_Kuesioner {
              width: 100%;
              font-size: 9px !important;
            }

            .inputkusioner {
              width: 50% !important;
              display: flex;
              justify-content: flex-end;
              align-items: flex-end;
            }

            #stypenilai {
              width: 15% !important;
            }

            #qtypemasangan {
              width: 100% !important;
            }

            #divkesimpulan {
              padding: 1px;
            }

            input[type="hidden"] {
              display: none !important;
            }

            tfoot td {
              padding: 1px;
            }

            @media print {
              @page {
                size: A4 portrait;
                margin: 10mm;
              }

              body {
                margin: 0;
              }

              #Print,
              #BatalBtn,
              #UpdateBtn,
              #DeleteBtn {
                display: none;
              }

              .mt-4 {
                margin-top: 2px !important;
              }

              #listdata {
                margin-right: 5px;
              }
            }
          </style>
        </head>
        <body>
          ${printArea.innerHTML}
        </body>
      </html>
    `);

    printWindow.document.close();
    printWindow.focus();

    setTimeout(() => {
      printWindow.print();
      printWindow.close();
    }, 1000);
  });
});



