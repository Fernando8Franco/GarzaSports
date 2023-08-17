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
    var idEvent, option;
    option = 4;

    dataTable = new DataTable("#datatable_events", {
      ajax: {
        url: "http://localhost/public/events/eventsCRUD",
        method: "POST",
        data: { option: option },
        dataSrc: "",
      },
      columns: [
        { data: "id", visible: false },
        { data: "name" },
        { data: "start_date" },
        { data: "end_date" },
        { data: "ins_start_date" },
        { data: "ins_end_date" },
        {
          defaultContent:
            "<div class='text-center'><button class='btn btn-primary btn-sm'>Editar  <i class='fa-solid fa-pen-to-square'></i></button><button class='btn btn-danger btn-sm'>Eliminar  <i class='fa-regular fa-trash-can'></i></button></div>",
        },
      ],
      language: dataTableLanguage,
    });

    dataTableIsInitialized = true;
  } catch (error) {
    console.error("Error:", error);
  }
};

document
  .getElementById("events_link")
  .addEventListener("htmx:afterRequest", async function () {
    await initDataTableEvents();

    document
      .getElementById("eventForm")
      .addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent the default form submission behavior (page reload)

        // Get form input values
        var name = document.getElementById("nameEvent").value.trim();
        var start_event = document.getElementById("start_event").value.trim();
        var end_event = document.getElementById("end_event").value.trim();
        var ins_start_event = document
          .getElementById("ins_start_event")
          .value.trim();
        var ins_end_event = document
          .getElementById("ins_end_event")
          .value.trim();

        if (name === "") {
          alert("Por favor, ingresa un nombre para el evento.");
          return;
        }

        if (
          start_event === "" ||
          end_event === "" ||
          ins_start_event === "" ||
          ins_end_event === ""
        ) {
          alert("Por favor, llenar todas las fechas");
          return;
        }

        var formData = new FormData();
        formData.append("id", idEvent);
        formData.append("name", name);
        formData.append("start_event", start_event);
        formData.append("end_event", end_event);
        formData.append("ins_start_event", ins_start_event);
        formData.append("ins_end_event", ins_end_event);
        formData.append("option", option);

        // Send data using Fetch API
        fetch("http://localhost/public/events/eventsCRUD", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            // Assuming tablaUsuarios is a DataTable, reload it
            dataTable.ajax.reload(null, false);

            var modal = new bootstrap.Modal(document.getElementById("modalEvent"));
            modal.hide();
          })
          .catch((error) => {
            console.error("Error:", error);
          });

          var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEvent'));
          myModal.hide();
      });

    document.getElementById("addEvent").addEventListener("click", function () {
      option = 1;
      idEvent = null;
      document.getElementById("eventForm").reset();
      document.querySelector(".modal-title").textContent = "Agregar Evento";
    });
  });
