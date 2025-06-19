
<?php

$userlog = $data['userid'];
$username = $data['username'];
?>
<style>
/* Hover effect & pointer */
.fc-daygrid-event {
  transition: 0.2s;
}
.fc-event-title-container:hover {
  background-color:rgb(242, 245, 240) !important;
  color:rgb(11, 12, 12) !important;
  cursor: pointer;
}

/* Dot warna di kiri */
.fc-event-title-container {
  background-color:white!important;
  color:rgb(11, 12, 12) !important;
}

/* Ubah warna tombol default */
.fc-button {
  background-color:  #343a40 !important;
  color: white;
  border: none;
}

/* Ubah warna saat hover */
.fc-button:hover {
  background-color:rgb(242, 245, 240) !important;
  color:rgb(11, 12, 12) !important;
}

/* Ubah warna tombol aktif */
.fc-button-active {
  background-color: #007bff !important;
  color: #fff !important;
}

.hover-putih:hover {
  color: white;
}

.fc-toolbar .fc-button {
    margin-right: 10px; /* atur sesuai kebutuhan */
  }

/* Hilangkan border default dari event */
.fc .fc-event {
  border: none !important;
  box-shadow: none !important;
}

/* Hilangkan outline (garis biru saat diklik atau difokus) */
.fc .fc-event:focus {
  outline: none !important;
}

/* Pastikan FullCalendar memanfaatkan lebar penuh */
.fc {
    width: 100% !important;
}

/* Sesuaikan ukuran font dan padding untuk mobile */
@media (max-width: 768px) {
    .fc-header-toolbar {
        font-size: 12px; /* Mengurangi ukuran font untuk tampilan mobile */
    }
    .fc-day-grid .fc-day {
        padding: 5px; /* Mengurangi padding untuk tampilan lebih kompak */
    }
    .fc-event {
        font-size: 12px; /* Mengurangi ukuran font acara */
        padding: 2px; /* Menyempitkan ruang antar acara */
    }

    .fc-toolbar-title {
        font-size: 14px !important;  /* Ukuran font lebih kecil dengan !important */
    }
    .fc-header-title {
        font-size: 14px !important;  /* Memastikan ukuran font title tetap kecil */
    }
}

</style>


<div id="main">
       <header class="mb-3">
       <input type="hidden" id="userid" class="form-control" value="<?=$userlog?>">
       <input type="hidden" id="username" class="form-control" value="<?=$username?>">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="section__content">
        <div class="container-fluid">
  
            <div class="row">
                <div class="col-lg-4">
                        <div class="card shadow rounded-4 overflow-hidden">
                          <!-- Header Biru -->
                          <div class="bg-primary text-white p-3 d-flex align-items-center">
                            <i class="bi bi-calendar-event-fill me-2 fs-4"></i>&nbsp;&nbsp;&nbsp;
                            <h5 class="mb-0"><?= date('d F, Y'); ?></h5>
                          </div>

                          <!-- Body Konten -->
                          <div class="card-body text-center py-5">
                          <div class="list-group">
                            <div id="listtoday"></div>
                          </div>
                          </div>
                        </div>
                      </div>

                    <div class="col-lg-8">
                        <div class="card card shadow rounded-4 overflow-hidden">
                            <div class="card-body">
                                <div id="yoo"></div>
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   



<!-- Modal tambah -->
<div class="modal fade" id="ModalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalTambahLabel">Tambah Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="container">
        <h4>Pasang Kampanye Tambah</h4>
      <form id="campaignForm">
        <input disabled type="hidden" id="tanggal" name="tanggal">
            <div id="kategori"></div>
            <div id="mediainput"></div>
        </form>
      </div>
      <div class="modal-footer">
        <div id="modeltombol"></div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- end Modal tambah -->

<!-- Modal Edit -->
<div class="modal fade" id="ModalEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalEditLabel">Edit Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="container">
        <h4>Pasang Kampanye Edit</h4>
      <form id="campaignFormEdit">
        <input disabled type="hidden" id="tanggal" name="tanggal">
            <div id="kategoriedit"></div>
            <div id="mediainputedit"></div>
        </form>
      </div>
      <div class="modal-footer">
        <div id="modeltomboledit"></div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- end Modal Edit -->
</div>

<script type="module" src="<?= base_url; ?>/assets/js/componets/classkampaye/index.js"></script>
