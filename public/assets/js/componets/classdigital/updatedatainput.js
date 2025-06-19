import { baseUrl } from '../config.js';
import{getTampilback} from './listdata.js';
import {conversionMonth} from '../setnamemonth.js';
// ===============================
// UPDATE DATA INPUT DIGITAL
// ===============================
export function Updatedatainput() {


    $(document).on("click","#UpdateBtn",async function(event){
        event.preventDefault();
         const datainput = await validasiInput();
          if(!datainput){
            return;
        }
           $.ajax({
                  url: `${baseUrl}/router/seturl`,
                  method: "POST",
                  dataType: "json",
                  data:datainput,
                  contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                  headers: { 'url': 'mont/updatedatadigital' },

                    beforeSend: function () {
                      Swal.fire({
                                  title: 'Loading',
                                  html: 'Please wait...',
                                  allowEscapeKey: false,
                                  allowOutsideClick: false,
                                  didOpen: () => {
                                  Swal.showLoading()
                              }
                                  });
             
                    },
                  success: function (result) {
                    if(result.nilai ==0){
                        const tahun = $("#filter_tahun").val();
                        const bulan = $("#filter_bulan").val();
                      const nameamount =conversionMonth(bulan);
                         Swal.fire({
                        icon: "warning",
                        title: "Data Duplikat!",
                        text: `Data untuk bulan ${nameamount} dan tahun ${tahun} tidak boleh berubah.`,
                        confirmButtonText: "OK"
                        });
                        return 
                    }
                        let pesan =result.error;
                         Swal.fire({
                          position: 'center',
                           icon: 'success',
                            showConfirmButton: false,
                            timer:1000,
                            text:pesan,
                             }).then(function(){
                                 goback();
                            });
                     
                
                  },
                  error: function () {
                         Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Terjadi kesalahan saat Simpan data."
                    });
                  // alert("Gagal mengambil data media.");
                  }
              });
    })


     $(document).on("click","#DeleteBtn",function(event){
        event.preventDefault();
        const TransDigitalID = $("#TransDigitalID").val();
        Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Hapus Data Ini!",
                type: "warning",
                showDenyButton: true,
                confirmButtonColor: "#DD6B55",
                denyButtonColor: "#757575",
                confirmButtonText: "Ya, Hapus!",
                denyButtonText: "Tidak, Batal!",
              }).then((result) =>{
                if (result.isConfirmed) {
                  $.ajax({
                            url: `${baseUrl}/router/seturl`,
                            type:'POST',
                            dataType:'json',
                            contentType: "application/x-www-form-urlencoded; charset=UTF-8", // 
                            headers: {
                                'url':'mont/deletedatadigital'
                            },
                            data :{"TransDigitalID":TransDigitalID},
                            beforeSend: function(){
                                Swal.fire({
                                  title: 'Loading',
                                  html: 'Please wait...',
                                  allowEscapeKey: false,
                                  allowOutsideClick: false,
                                  didOpen: () => {
                                  Swal.showLoading()
                              }
                                  });
                              },
                            success:function(result){
                        
                              let status = result.error;
                              Swal.fire({
                                position: 'center',
                              icon: 'success',
                              title: status,
                              showConfirmButton: false,
                              timer: 1000
                              }).then(function(){
                                goback();
                              })
                            },
                             error: function () {
                                    Swal.fire({
                                    icon: "error",
                                    title: "Error!",
                                    text: "Terjadi kesalahan saat Delete data."
                                });
                             }
                          });
                    }
              })
              
    })
}


// Fungsi validasi dan pengumpulan data input
    const validasiInput = async () => {
        const tahun = $("#filter_tahun").val();
        const bulan = $("#filter_bulan").val();
        const TransDigitalID = $("#TransDigitalID").val();
 // Validasi data ke server dulu
       try {
            const validasi = await cekpalidasivata(tahun, bulan,TransDigitalID);
            if (validasi.duplicate) {

                const nameamount =conversionMonth(bulan);
             Swal.fire({
                icon: "warning",
                title: "Data Duplikat!",
                text: `Data untuk bulan ${nameamount} dan tahun ${tahun} sudah ada.`,
                confirmButtonText: "OK"
                });
                return null; // Hentikan proses
            }
        } catch (err) {
            console.log(err)
             Swal.fire({
                icon: "error",
                title: "Gagal Validasi!",
                text: "Tidak bisa memeriksa duplikasi data. Silakan coba lagi.",
                confirmButtonText: "OK"
            });
            return null;
        }
         // Ambil data kampanye digital
        const kampanyeArray = ambilDataTabelKampanyeDigital("#table_digital > tbody > tr");

        const datagabung ={
            TransDigitalID:TransDigitalID,
            tahun:tahun,
            bulan:bulan,
            datadetail:kampanyeArray
        }

        return datagabung;

    }


const cekpalidasivata = (tahun, bulan,TransDigitalID) => {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: `${baseUrl}/router/seturl`,
            method: "POST",
            dataType: "json",
            data: { "tahun": tahun,"bulan": bulan,"TransDigitalID":TransDigitalID },
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            headers: { 'url': 'mont/validasieditdigital' },
            success: function (result) {
                resolve(result);
            },
            error: function (xhr, status, error) {
                reject(error);
            }
        });
    });
};
     // Fungsi bantu ambil data kampanye digital
    const ambilDataTabelKampanyeDigital = (selector) => {
        const data = [];

        $(selector).each(function () {
            const idMedia = $(this).find('td:eq(1)').attr("id");
            const pemasangan = $(this).find('td:eq(2)').text().trim();
            const jml_view   = $(this).find('input[name="view"]').val();
            const jml_follow   = $(this).find('input[name="follower"]').val();
            const catatan = $(this).find('textarea[name="cataankampanye"]').val()?.replace(/,/g, "").trim() || "";

            if (!idMedia || !pemasangan) return;

            data.push({
                id_media: idMedia,
                pemasangan: pemasangan,
                view:jml_view,
                follower :jml_follow,
                catatan: catatan
            });
        });

        return data;
    };

  const goback =()=>{
       const tahun = $("#filter_tahun").find(":selected").val();
        const bulan = $("#filter_bulan").find(":selected").val();
               
                       const datas ={
                           "tahun" :tahun,
                           "bulan" :bulan
                       }
                       $("#listdata").empty();
                        getTampilback(datas);
    }