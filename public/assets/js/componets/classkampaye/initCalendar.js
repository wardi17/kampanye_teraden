import { baseUrl } from '../config.js'; // sesuaikan path
import { getkategoriEdit } from './kategoriedit.js';
import { getlisttoday } from './listtoday.js';

export function getTampilData() {
  $.ajax({
    url: `${baseUrl}/router/seturl`,
    method: "GET",
    dataType: "json",
    headers: { 'url': 'kamp/tampildata' },
    success: function (result) {
      const dataCalendar = result?.map(b => {
        return {
          title: `${b.name} ${b.media}`,
          start: b.tanggal,
          ...b
        };
      });

      const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,listWeek'
        },
        events: dataCalendar,
        eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: false },
        eventDisplay: 'auto',
        eventDidMount: handleEventMount,
        dateClick: handleDateClick,
        eventClick: handleEventClick
      });
      calendar.render();
    }
  });
       getlisttoday();
}



function handleEventMount(info) {
  const eventDate = new Date(info.event.start);
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  eventDate.setHours(0, 0, 0, 0);

  if (eventDate < today) {
    const title = info.el.querySelector('.fc-event-title');
    if (title) title.style.color = "red";
  }
}

function handleDateClick(info) {
  $("#tanggal").val(info.dateStr);
  $("#ModalTambah").modal("show");
}

function handleEventClick(info) {
  const props = info.event.extendedProps;
  $("#ModalEdit").modal("show");

    getkategoriEdit(props)
  
}


