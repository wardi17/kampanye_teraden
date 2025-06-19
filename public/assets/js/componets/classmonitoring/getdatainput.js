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
            headers: { 'url': 'mont/getmonio' },
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
        let html =``;
        
          html +=`<h5>Kampanye</h5>`;

        html +=`<table id="table_kampanye" class="table table-responsive">
                    <thead class="highlight">
                    <tr> 
                       <th class="text-start">No</th>
                       <th class="text-center">Nama Media</th>
                       <th class="text-center">Pemasangan</th>
                       <th class="text-center">Catatan</th>
                    </tr>
                    </thead><tbody>`;
         let no =1;
        $.each(kampanye,function(a,item){
                const{nama_media,terpasang,...itemRest}=item
                html +=`<tr>
                        <td>${no++}</td>
                        <td class="text-center"id="${itemRest.id_media}">${nama_media}</td>
                        <td class="text-center">${terpasang}</td>
                        <td class="text-center"><textarea name="cataankampanye" class="form-control"></textarea></td>
                </tr>`;
        })
         html += `</tbody>
                </table>
                `;
        html +=` <div class="row col-md-12 mb-4">
                <label for="kesimpulan_kampanye" class="col-sm-2 form-label">Kesimpulan</label>
                <div class="col-sm-4">
                    <textarea id="kesimpulan_kampanye" class="form-control" style="width:150%;"></textarea>
                <span id="kesimpulan_kampanyeError" class="error"></span>
                </div>
            </div>`
         $("#kampanye").empty().html(html);
    }


const settableKuesioner = (kuesioner) => {
    const grouped = {};

    // Kelompokkan berdasarkan id_media
    kuesioner.forEach(item => {
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
                  text:item.Keterangan.replace("Pertanyaan: ", "").trim()
            });
        } else if (item.Detail) {
            grouped[id].pilihan.push({
                id:item.id_Detail,
                text:item.Detail.trim()
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
        <div class="col-sm-1">
            <input class="form-control text-end" id="qtypemasangan" type="number">
            <span id="qtypemasanganError" class="error"></span>
        </div>
        </div>
    `;

    // Tambahkan pertanyaan dan pilihan
    let groupidnilai='';
    let nonilai =0;
   
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
        html += `<div class="col-md-12 mb-3 pertanyaan">`;
        html += `<h5>${text_p }</h5>`;
        html +=`<input type="hidden" id="mspertanyaan${nonilai}" class="form-control" value="${id_p}">`;
        html += `<table id="${substrid}" class="table table-responsive" style="width:100%">
                <thead> 
                <tr>
                <th class="text-center col-1">No</th>
                 <th class="text-start col-6">Pilihan</th>
                 <th class="text-end col-2">Bayak Kuesioner</th>
                 <th class="text-end col-2">Persen %</th>
                </tr>
                </thead><tbody>
        `;
        let no =1;
        group.pilihan.forEach(pilihan => {
              let setid =pilihan.id.replace(/\./g, "");
   
         let idnilai = setid+"Nilai";
         let idpersen = setid+"Persen";
       
         groupidnilai +=idnilai+'|'+idpersen+'|'+idtotal+',';

       

            html += `<tr>
                    <td class="text-center col-1">${no++}</td>
                    <td class="col-8" id="${pilihan.id}">${pilihan.text}</td>
                    <td  style="display: flex; justify-content: end; align-items: center;"><input style="width:50%;" type="number" name="nilai${nonilai}" class="col-2 form-control text-end ${nonilai}nilai" id="${idnilai}">
                         <span id="${idnilai}Error" class="error"></span>
                    </td>
                    <td class="col-2"><input type="number" name="persen${nonilai}" disabled class="form-control text-end  ${nonilai}persen" id="${idpersen}"></td>
                    </tr>`;
          
        });
         html +=`</tbody> <tfoot>
                <tr>
                <th colspan=2 class="text-end"> Total :</th>
                 <th class="text-end cltotal" id="${idtotal}"></th>
                <th class="text-end" id="${idtotalpresen}"></th>

                </tr>
            `;
        html += `</tfoot></table>`;
        html += `</div>`;
    });

    // Tambahkan textarea kesimpulan
    html += `
        <div class="row col-md-12 mb-3">
            <label for="kesimpulan" class="col-sm-2 form-label">Kesimpulan</label>
            <div class="col-sm-4">
                <textarea id="kesimpulan" class="form-control" style="width:150%;"></textarea>
                <span id="kesimpulanError" class="error"></span>
            </div>
        </div>
    `;

    html +=`<div class="col-sm-11 d-flex justify-content-end mt-4">
                                   <button class="btn btn-primary me-1 mb-3" id="SubmitBtn">Simpan</button>
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
        let tt =total.toString() + "&nbsp;&nbsp;";
        let id_tt = "#Total_"+nilai;
        $(id_tt).html(tt);
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
        $(id_ttps).html(to_presen);
    }


  function formatjm(angka){
    return parseFloat(angka.replace(/[^0-9.]/g, ''));
  }



    
