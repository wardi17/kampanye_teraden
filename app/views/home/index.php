<div id="main">
       <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
    <!-- Content Header (Page header) -->
    <div class ="col-md-12 col-12">


      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Hello</h3>
          <hr>
        </div>
        <div class="card-body">
          Selamat datang dihalaman Kampanye Teraden
        </div>
        <!-- /.card-body -->
  
      <!-- /.content -->
  </div>
</div>
</div>
<script >

function getFirstWeekDates(month, year) {
 
    // Bulan dalam JavaScript dimulai dari 0 (Januari = 0, Februari = 1, dst.)
    const firstDay = new Date(year, month - 1, 1);
    const firstWeekDates = [];

    // Cari hari dalam minggu pertama (Senin sampai Minggu)
    // Ubah agar minggu dimulai dari Senin (getDay() di JS: Minggu = 0, Senin = 1, dst.)
    let dayOfWeek = firstDay.getDay();

    if (dayOfWeek === 0) dayOfWeek = 7;  // Jika Minggu, ubah ke 7

    // Tentukan tanggal terakhir dari minggu pertama
    const endOfWeek = new Date(firstDay);
    
    endOfWeek.setDate(firstDay.getDate() + (7 - dayOfWeek));
  
    // Iterasi dari tanggal 1 sampai akhir minggu pertama
    let currentDate = new Date(firstDay);
    while (currentDate <= endOfWeek) {
        firstWeekDates.push(currentDate.toISOString().split('T')[0]);  // Format YYYY-MM-DD
        currentDate.setDate(currentDate.getDate() + 1);  // Tambah 1 hari
    }

    console.log(firstWeekDates);
    return firstWeekDates;
}

// Contoh penggunaan
const month = 2;  // Februari
const year = 2025;

const firstWeek = getFirstWeekDates(month, year);
//console.log("Tanggal di minggu pertama:", firstWeek);

</script>