document.addEventListener("DOMContentLoaded", function (event) {
  const showNavbar = (toggleId, navId, bodyId, headerId) => {
    const toggle = document.getElementById(toggleId),
      nav = document.getElementById(navId),
      bodypd = document.getElementById(bodyId),
      headerpd = document.getElementById(headerId);

    // Validate that all variables exist
    if (toggle && nav && bodypd && headerpd) {
      toggle.addEventListener("click", () => {
        // show navbar
        nav.classList.toggle("show");
        // change icon
        toggle.classList.toggle("fa-x");
        // add padding to body
        bodypd.classList.toggle("body-pd");
        // add padding to header
        headerpd.classList.toggle("body-pd");
      });
    }
  };

  showNavbar("header-toggle", "nav-bar", "body-pd", "header");

  /*===== LINK ACTIVE =====*/
  const linkColor = document.querySelectorAll(".nav_link");

  function colorLink() {
    if (linkColor) {
      linkColor.forEach((l) => l.classList.remove("active"));
      this.classList.add("active");
    }
  }
  linkColor.forEach((l) => l.addEventListener("click", colorLink));

  // Your code to run since DOM is loaded and ready
});

const dataTableLanguage = {
  sProcessing: "Procesando...",
  sLengthMenu: "Mostrar _MENU_ registros",
  sZeroRecords: "No se encontraron resultados",
  sEmptyTable: "Ningún dato disponible en esta tabla",
  sInfo:
    "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
  sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
  sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
  sSearch: "Buscar:",
  sInfoThousands: ",",
  sLoadingRecords: "Cargando...",
  oPaginate: {
    sFirst: "Primero",
    sLast: "Último",
    sNext: "Siguiente",
    sPrevious: "Anterior",
  },
};

let dataTable;
let dataTableIsInitialized = false;

const initDataTableEvents = async () => {
  if (dataTableIsInitialized) {
    dataTable.destroy();
  }

  try {
    const response = await fetch("http://localhost/public/events/tables");
    if (!response.ok) {
      throw new Error("Error fetching data");
    }

    const data = await response.json();

    // Aquí 'data' contendrá los datos en formato JSON
    console.log(data);

    dataTable = new DataTable("#datatable_events", {
      data: data,
      columns: [
        { data: "name" },
        { data: "start_date" },
        { data: "end_date" },
        { data: "ins_start_date" },
        { data: "ins_end_date" },
      ],
      language: dataTableLanguage,
    });

    dataTableIsInitialized = true;
  } catch (error) {
    console.error("Error:", error);
  }
};

document.getElementById('events_link').addEventListener('htmx:afterRequest', async function () {
  await initDataTableEvents();
});

$(document).ready(function() {
  // This code initializes the modal and handles its behavior
  $('#addEvent').click(function() {
    $('#modalEvent').modal('show');
  });
});
