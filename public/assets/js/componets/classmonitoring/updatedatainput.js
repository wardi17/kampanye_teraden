import { baseUrl } from '../config.js';
import{getTampilback} from './listdata.js';
import{conversionMonth} from '../setnamemonth.js';
// ===============================
// SIMPAN DATA INPUT MANUAL DAN  KUISONER
// ===============================
export function Updatedatainput() {
    
    // Event listener untuk tombol submit
    $(document).on("click", "#UpdateBtn", async function (event) {
        event.preventDefault();

        const dataGabungan = await validasiInput();
        if(!dataGabungan){
            return;
        }
                 $.ajax({
                  url: `${baseUrl}/router/seturl`,
                  method: "POST",
                  dataType: "json",
                  data:dataGabungan,
                  contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                  headers: { 'url': 'mont/updatedata' },

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
                        text: "Terjadi kesalahan saat update data."
                    });
                  // alert("Gagal mengambil data media.");
                  }
              });

        // Kirim data ke server di sini kalau diperlukan
    });

    $(document).on("click","#DeleteBtn",function(event){
        event.preventDefault();
        const MonitoringID = $("#MonitoringID").val();
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
                                'url':'mont/deletedata'
                            },
                            data :{"MonitoringID":MonitoringID},
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
    
    // Fungsi validasi dan pengumpulan data input
    const validasiInput = async () => {
        const tahun = $("#filter_tahun").val();
        const bulan = $("#filter_bulan").val();
        const MonitoringID = $("#MonitoringID").val();
        const kesimpulanKamp = $("#kesimpulan_kampanye").val().trim();

           // Validasi data ke server dulu
               try {
                    const validasi = await cekpalidasivata(tahun, bulan,MonitoringID);

                    if (validasi.nilai === 0) {
        
                        const nameamount =conversionMonth(bulan);
                     Swal.fire({
                        icon: "warning",
                        title: "Data Duplikat!",
                        text: `Data untuk bulan ${nameamount} dan tahun ${tahun} tidak boleh berubah.`,
                        confirmButtonText: "OK"
                        });
                        return null; // Hentikan proses
                    }
                } catch (err) {
                     Swal.fire({
                        icon: "error",
                        title: "Gagal Validasi!",
                        text: "Tidak bisa memeriksa  data. Silakan coba lagi.",
                        confirmButtonText: "OK"
                    });
                    return null;
                }
        // Ambil data kampanye manual
        const kampanyeArray = ambilDataTabelKampanye("#table_kampanye > tbody > tr");

        const dataHasilKampanye = {
            kesimpulan: kesimpulanKamp,
            datadetail: kampanyeArray
        };

        // Ambil data kuisoner 1
        const dataHasilPertanyaan1 = ambilDataKuisoner({
            idTabel         : "#MPP25001 > tbody > tr",
            nilaiSelector   : 'input[name="nilai1"]',
            persenSelector  : 'input[name="persen1"]',
            qty             : $("#qtypemasangan").val(),
            totalNilai      : $("#Total_1nilai").val().trim(),
            totalPersen     : $("#Total_1persen").val().trim(),
            msid            : 1
        });

        // Ambil data kuisoner 2
        const dataHasilPertanyaan2 = ambilDataKuisoner({
            idTabel         : "#MPP25002 > tbody > tr",
            nilaiSelector   : 'input[name="nilai2"]',
            persenSelector  : 'input[name="persen2"]',
            qty             : $("#qtypemasangan").val(),
            totalNilai      : $("#Total_2nilai").val().trim(),
            totalPersen     : $("#Total_2persen").val().trim(),
             msid            : 2
        });


        const kesimpulanQuisoner = $("#kesimpulan").val();

        const dataHasilKuisoner = {
            kesimpulanQuisoner: kesimpulanQuisoner,
            dataHasilPertanyaan1: dataHasilPertanyaan1,
            dataHasilPertanyaan2: dataHasilPertanyaan2
        };

        // Gabungkan semua data
        const dataGabungan = {
            tahun: tahun,
            bulan: bulan,
            MonitoringID:MonitoringID,
            datakampanye: dataHasilKampanye,
            dataquesioner: dataHasilKuisoner
        };

        return dataGabungan;
    };

    // Fungsi validasi input data ke server dengan AJAX + Promise
        const cekpalidasivata = (tahun, bulan,MonitoringID) => {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: `${baseUrl}/router/seturl`,
                    method: "POST",
                    dataType: "json",
                    data: { tahun: tahun, bulan: bulan,MonitoringID:MonitoringID},
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    headers: { 'url': 'mont/validasiedit' },
                    success: function (result) {
                        resolve(result);
                    },
                    error: function (xhr, status, error) {
                        reject(error);
                    }
                });
            });
        };
    // Fungsi bantu ambil data kampanye manual
    const ambilDataTabelKampanye = (selector) => {
        const data = [];

        $(selector).each(function () {
            const idMedia = $(this).find('td:eq(1)').attr("id");
            const pemasangan = $(this).find('td:eq(2)').text().trim();
            const catatan = $(this).find('textarea[name="cataankampanye"]').val()?.replace(/,/g, "").trim() || "";

            if (!idMedia || !pemasangan) return;

            data.push({
                id_media: idMedia,
                pemasangan: pemasangan,
                catatan: catatan
            });
        });

        return data;
    };

    // Fungsi bantu ambil data kuisoner
    const ambilDataKuisoner = ({ idTabel, nilaiSelector, persenSelector, qty, totalNilai, totalPersen,msid }) => {
        const dataDetail = [];
        let idms = `#mspertanyaan${msid}`;
         const mspertanyaid = $(idms).val();
        $(idTabel).each(function () {
            const idPertanyaan = $(this).find('td:eq(1)').attr("id");
            const nilai = $(this).find(nilaiSelector).val();
            const presen = $(this).find(persenSelector).val();

            if (!idPertanyaan || !presen) return;

            dataDetail.push({
                idPertanyaan: idPertanyaan,
                nilai: nilai,
                presen: presen
            });
        });

    
        return {
            mspertanyaid:mspertanyaid,
            qtypemasangan: qty,
            totalnilai: totalNilai,
            totalpersen: totalPersen,
            datadetail: dataDetail
        };
    };
}

