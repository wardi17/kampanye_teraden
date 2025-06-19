import { baseUrl } from '../config.js';
import { getTampilData } from './initCalendar.js';
let uploadedFiles = [];
let katgoris ="";
// ===============================
// GET KATEGORI & HANDLE PILIHAN
// ===============================
 let datakategoriedit = null;
export function getkategoriEdit(props) {
 
   const idkategori =props.idkategori;
   const namakategori=props.kategori;
   datakategoriedit =props;
    katgoris =idkategori;
    let html = `
      <div class="row mb-3">
        <label for="kategori" class="col-sm-1 col-form-label">Kategori</label>
        <div class="col-sm-10 d-flex flex-wrap gap-3 align-items-center">
    `;

      html += `
        <div class="form-check form-check-inline">
          <input disabled class="form-check-input" type="radio" name="kategori" id="${idkategori}" value="${idkategori}" checked>
          <label class="form-check-label" for="${idkategori}">${namakategori}</label>
        </div>
      `;

    html += `</div></div>`;
    $("#kategoriedit").empty().html(html);

  // Ketika kategori dipilih

   const datas = {"kategori": idkategori};
    renderFormManual(datas);
  
  

}

// ===============================
// RENDER FORM MANUAL (INPUTAN)
// ===============================
const renderFormManual = (datas) => {
  const ktg =datakategoriedit;
  const kategori = datas.kategori;
   let label='';
  let formHTML = `
    <div class="row col-md-12-col-12">

      <!-- Media -->
      <div class="row col-md-12 mb-3">
        <label for="media" class="col-sm-2 form-label">Media</label>
        <div class="col-sm-4">
          <select class="form-control text-start" id="media"></select>
        </div>
      </div>

      <!-- Nama Kampanye -->
      <div class="row col-md-12 mb-3">
        <label for="namakampanye" class="col-sm-2 form-label">Nama kampanye</label>
        <div class="col-sm-4">
          <input id="namakampanye" name="namakampanye" value="${ktg.name}" type="text" class="form-control">
          <span id="namakampanyeError" class="error"></span>
        </div>
      </div>

      <!-- Wilayah -->
      <div class="row col-md-12 mb-3">
        <label for="wilayah" class="col-sm-2 form-label">Wilayah</label>
        <div class="col-sm-4">
          <input disabled id="wilayah" name="wilayah" type="text" value="KLM" class="form-control">
          <span id="wilayahError" class="error"></span>
        </div>
      </div>`;

      if(kategori ==="JM.001"){
        label='UpdateDataManual';
    formHTML +=`
     <!-- Lokasi -->
      <div class="row col-md-12 mb-3">
        <label for="lokasi" class="col-sm-2 form-label">Lokasi</label>
        <div class="col-sm-4">
          <input id="lokasi" value="${ktg.lokasi}" name="lokasi" type="text" class="form-control">
          <span id="lokasiError" class="error"></span>
        </div>
      </div>

      <!-- Attach File -->
      <div class="row col-md-12 mb-3">
        <label for="ticket-attachment" class="col-sm-2 col-form-label">Attach File</label>
        <div class="col-md-9">
          <div class="col-sm-8 mt-0" id="uploadfile">
            <label style="cursor:pointer" for="attach">
              <i class="fa-solid fa-file-arrow-up fa-2x"></i>
              <input class="col-md-1" id="attach" name="files[]" type="file" multiple>
            </label>
            <p class="form-text text-muted"><em>Valid file type: .jpg | File size max: 1 MB</em></p>
          </div>
          <div id="tampil_attach" class="row mt-0"></div>
        </div>
      </div>`;
    }else{
        label='UpdateDataDigital';
      formHTML  +=` 
      <!-- link -->
      <div class="row col-md-12 mb-3">
        <label for="link" class="col-sm-2 form-label">Link</label>
        <div class="col-sm-8">
          <input id="link" name="link" type="url"  value="${ktg.documen_file}" class="form-control">
          <span id="linkError" class="error"></span>
        </div>
      </div>`
    }

 formHTML +=`
      <!-- Catatan -->
      <div class="row mb-12 mb-2">
        <label for="ket" class="col-sm-2 col-form-label">Catatan</label>
        <div class="col-sm-4">
          <textarea id="ket" class="form-control" value="${ktg.ket}" style="width:150%;">${ktg.ket}</textarea>
          <span id="ketError" class="error"></span>
        </div>
      </div>

    </div>
  `;

  const btnHTML= `
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      <button type="button" class="btn btn-info" id="${label}">Update</button>
      <button type="button" class="btn btn-danger" id="DeleteData">Delete</button>
    `;
 // Event input file jika kategori JM.001
  if (kategori === "JM.001") {
  
    handleFileAttachment();
  }

    // Tampilkan form dan tombol
  $("#mediainputedit").empty().html(formHTML);
  $("#modeltomboledit").empty().html(btnHTML);

  // Load media dari server
  getmedia(datas);

  // Tampilkan preview file jika kategori JM.001 dan ada file
if (kategori === "JM.001" && ktg.documen_file) {
   $("#uploadfile").fadeOut();
  const fileName = ktg.documen_file;
  const fileId = "file-preview-1";
  const fileURL = `${baseUrl}/uploads_attachfile/${fileName}`;

  const htmlPreview = `
    <div class="col-md-3 position-relative text-start p-2 d-inline-flex align-items-center" id="${fileId}">
      <a href="${fileURL}" target="_blank" class="text-decoration-none">${fileName}</a>
      <button class="btn btn-sm text-danger ms-2 remove-file" data-id="${fileId}" style="border: none; background: transparent;">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>`;

  $("#tampil_attach").html(htmlPreview);

  // Event hapus file preview
  $(document).on('click', '.remove-file', function () {
    const removeId = $(this).data('id');
    $(`#${removeId}`).remove();
     $("#uploadfile").fadeIn();
  });
}


};

// ===============================
// HANDLE FILE UPLOAD DAN PREVIEW
// ===============================
const handleFileAttachment = () => {

  $(document).on("change","#attach", function (event) {
    $("#uploadfile").fadeOut();
    let files = Array.from(event.target.files);
    let tampilAttach = $("#tampil_attach");

    files.forEach((file) => {
      if (file.size > 1024 * 1024) {
        $("#uploadfile").fadeIn();
        alert(`File ${file.name} terlalu besar (max 1 MB)`);
        return;
      }

      let fileId = `file-${uploadedFiles.length + 1}`;
      uploadedFiles.push({ id: fileId, file });

      let fileURL = URL.createObjectURL(file);
      let preview = `
        <div class="col-md-3 position-relative text-start p-2 d-inline-flex align-items-center" id="${fileId}">
          <a href="${fileURL}" target="_blank" class="text-decoration-none">${file.name}</a>
          <button class="btn btn-sm text-danger ms-2 remove-file" data-id="${fileId}" style="border: none; background: transparent;">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
      `;
      tampilAttach.append(preview);
    });

    $(this).val(""); // reset input file
  });

  // Hapus file yang di-preview
  $(document).on("click", ".remove-file", function (e) {
    e.preventDefault();
    $("#uploadfile").fadeIn();
    let fileId = $(this).data("id");
    $(`#${fileId}`).remove();
    uploadedFiles = uploadedFiles.filter(f => f.id !== fileId);
  });
};

// ===============================
// GET MEDIA UNTUK SELECT BOX
// ===============================
const getmedia = (datas) => {
  $.ajax({
    url: `${baseUrl}/router/seturl`,
    method: "POST",
    dataType: "json",
    data: datas,
    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
    headers: { 'url': 'kamp/getmedia' },
    success: function (result) {
          const idmedia = datakategoriedit.id_media;
      if (result && Array.isArray(result)) {
        let defaultOption = `<option value="" disabled ${!idmedia ? 'selected' : ''}>-- Pilih Media --</option>`;
        let mediaOptions = result.map(item =>{
          let selected = item.id == idmedia ? 'selected' :'';
          return `<option value="${item.id}" ${selected}>${item.name}</option>`
        }).join('');

        $("#media").html(defaultOption+mediaOptions);
      }
    },
    error: function () {
      alert("Gagal mengambil data media.");
    }
  });

};


// ===============================
// DELETE DATA 
// ===============================
 $(document).on("click","#DeleteData",function(event){
        const KampanyeID =datakategoriedit.KampanyeID;
        const documen_file=datakategoriedit.documen_file;
        const datas ={"KampanyeID":KampanyeID,"documen_file":documen_file};
         
        event.preventDefault();

            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Akan Menghapus Data ini",
                type: "warning",
                showDenyButton: true,
                confirmButtonColor: "#f7623c",
                denyButtonColor: "#757575",
                confirmButtonText: "Ya, Hapus!",
                denyButtonText: "Tidak, Batal!",
              }).then((result) =>{
                if(result.isConfirmed){
                  DeleteData(datas);
                }
          });
 })

    const DeleteData=(datas)=>{
              $.ajax({
             url: `${baseUrl}/router/seturl`,
              method: "POST",
              dataType: "json",
              data:datas,
              contentType: "application/x-www-form-urlencoded; charset=UTF-8",
              headers: { 'url': 'kamp/deletedata' },
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
                     $("#ModalEdit").modal("hide");
                        let pesan =result.error;
                                Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer:1000,
                                        text:pesan,
                                        }).then(function(){
                                         getTampilData();
                                        });
                          },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Terjadi kesalahan saat Hapus data."
            });
            },
         
        }); 
    }

// ===============================
// UPDATE DATA MANUAL
// ===============================
 $(document).on("click","#UpdateDataManual",function(event){
        event.preventDefault();

       const ktg =datakategoriedit;

       const KampanyeID  = ktg.KampanyeID
       const documen_file = ktg.documen_file
       const kategori     = katgoris;
       const media        = $("#media").find(":selected").val();
       const namakampanye = $("#namakampanye").val();
       const wilayah      = $("#wilayah").val();
       const lokasi       = $("#lokasi").val();
       const ket          = $("#ket").val();
       const tanggal      = $("#tanggal").val();
   
       const datas ={
          "KampanyeID"    :KampanyeID,
          "documen_file"  :documen_file,
          "kategori"      :kategori,
          "media"         :media,
          "namakampanye"  :namakampanye,
          "wilayah"       :wilayah,
          "lokasi"        :lokasi,
          "ket"           :ket,
          "tanggal"       :tanggal

       }

      
      
        const formData = new FormData();
        formData.append("datas", JSON.stringify(datas)); // Ubah objek ke JSON string
        uploadedFiles.forEach(fileObj => {
            formData.append("files[]", fileObj.file);
        });
      

        UpdateManual(formData);

    })

    const UpdateManual=(formData)=>{
        $.ajax({
             url: `${baseUrl}/router/seturl`,
             method: "POST",
             data: formData,
              processData: false, // Jangan memproses data
             contentType: false, // Jangan set header contentType
            dataType:'json',
            headers: { 'url': 'kamp/updatedatamanual' },
          
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
                // Tampilkan loader jika ada
                    $("#UpdateDataManual").prop("disabled", true).text("Menyimpan...");
                    },
                  success: function (result) {
                     $("#ModalEdit").modal("hide");
                        let pesan =result.error;
                                Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer:1000,
                                        text:pesan,
                                        }).then(function(){
                                          resterinputan();
                                         getTampilData();
                                        });
                          },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Terjadi kesalahan saat menyimpan data."
            });
            },
            complete: function () {
            // Aktifkan tombol kembali
            $("#UpdateDataManual").prop("disabled", false).text("Update");
            }
        });
    }

    const resterinputan=()=>{
           $('#campaignForm')[0].reset();
          $('#mediainput').empty();
          $('#modeltombol').empty();
          $('#tampil_attach').empty();
          $('#uploadfile').show();
          uploadedFiles = [];
    }

  // ===============================
// UPDATE DATA DIGITAL
// ===============================

 $(document).on("click","#UpdateDataDigital",function(event){
        event.preventDefault();
       const ktg =datakategoriedit;
      const KampanyeID  = ktg.KampanyeID
       const kategori     = katgoris;
       const media        = $("#media").find(":selected").val();
       const namakampanye = $("#namakampanye").val();
       const wilayah      = $("#wilayah").val();
       const ket          = $("#ket").val();
       const tanggal      = $("#tanggal").val();
       const link         = $("#link").val();
      
       const datas ={
          "KampanyeID"    :KampanyeID,
          "media"         :media,
          "namakampanye"  :namakampanye,
          "wilayah"       :wilayah,
          "link"          :link,
          "ket"           :ket,

       }

        if (link.length === 0) {
              Swal.fire({
              position: 'center',
              icon: 'info',
              showConfirmButton: false,
              timer:10,
              text:"Silakan input link terlebih dahulu!",
              })
            return;
        }

  
        const formData = new FormData();
        formData.append("datas", JSON.stringify(datas)); // Ubah objek ke JSON string
     

        UpdateDigital(formData);

    });

    const UpdateDigital =(formData)=>{
         $.ajax({
             url: `${baseUrl}/router/seturl`,
             method: "POST",
             data: formData,
              processData: false, // Jangan memproses data
             contentType: false, // Jangan set header contentType
            dataType:'json',
            headers: { 'url': 'kamp/updatedatadigital' },
          
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
                // Tampilkan loader jika ada
                    $("#UpdateDataManual").prop("disabled", true).text("SedangUpdatae...");
                    },
                  success: function (result) {
                     $("#ModalEdit").modal("hide");
                        let pesan =result.error;
                                Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer:1000,
                                        text:pesan,
                                        }).then(function(){
                                          resterinputanmanual();
                                         getTampilData();
                                        });
                          },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Terjadi kesalahan saat update data."
            });
            },
            complete: function () {
            // Aktifkan tombol kembali
            $("#UpdateDataManual").prop("disabled", false).text("Update");
            }
        });
    }

        const resterinputanmanual=()=>{
          $('#mediainput').empty();
          $('#modeltombol').empty();
    }