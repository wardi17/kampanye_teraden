// index.js

import { getTampilData } from './initCalendar.js';
import { getkategori } from './kategori.js';




$(document).ready(function () {
     getTampilData();

  $('#ModalTambah').on('show.bs.modal', function () {
     getkategori();
   
  });


    $(document).on('click', '[data-bs-dismiss="modal"]', function () {
        setTimeout(() => {
          $('#campaignForm')[0].reset();
          $('#mediainput').empty();
          $('#modeltombol').empty();
          $('#tampil_attach').empty();
          $('#uploadfile').show();
        }, 300); // Delay biar animasi modal selesai
      });


  
});

