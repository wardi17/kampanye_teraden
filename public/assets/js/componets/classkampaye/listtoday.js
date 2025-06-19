import { baseUrl } from '../config.js';

// ===============================
// GET LISTTODAY 
// ===============================
export function getlisttoday() {

      $.ajax({
        url: `${baseUrl}/router/seturl`,
        method: "GET",
        dataType: "json",
        contentType: "application/x-www-form-urlencoded; charset=UTF-8",
        headers: { 'url': 'kamp/getlisttoday' },
        success: function (result) {

        let html =``;
             if(result ==null){
          html +=` <img src="${baseUrl}/assets/img/kampanye.png" alt="No Meeting" class="img-fluid mb-4" style="max-height:300px; width:auto;">
                            <p class="text-muted fs-6">Tidak Ada Kampanye  Untuk Hari Ini</p>`;
 
        }else{
          $.each(result, function(a,b){
   
            const {name,media,kategori} =b
			html+=`
                    <div class="list-group-item border-0 list-group-item-action text-center">
                <!-- Platform -->
                <h6 class="mb-1">
                  <a href="javascript:void(0);" class="text-decoration-none text-primary">${kategori}</a>
                </h6>

                <!-- Waktu -->
                <small class="text-muted d-block mb-2">${media}</small>

                <!-- Topik -->
                <h6 class="mb-0">
                  <a href="javascript:void(0);" class="text-decoration-none text-primary">${name}</a>
                </h6>
              </div>
              `;

						});
        }

        $('#listtoday').html(html);
        }
    });
}