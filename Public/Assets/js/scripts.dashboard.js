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

function convertMonth(month) {
  const months = {
    "Ene": "01", "Feb": "02", "Mar": "03", "Abr": "04", "May": "05", "Jun": "06",
    "Jul": "07", "Ago": "08", "Sep": "09", "Oct": "10", "Nov": "11", "Dic": "12"
  };
  return months[month];
}

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
            "<div class='text-center'><button class='btn btn-primary btn-sm editBtn'>Editar  <i class='fa-solid fa-pen-to-square'></i></button><button class='btn btn-danger btn-sm deleteBtn'>Eliminar  <i class='fa-regular fa-trash-can'></i></button></div>",
        },
      ],
      order: [[0, "desc"]],
      columnDefs: [
        {
          targets: 1, // Target the "name" column (index 1)
          orderData: [0], // Use data from the "id" column (index 0) for ordering
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
      .addEventListener("submit", async (e) => {
        e.preventDefault(); // Prevent the default form submission behavior (page reload)

        const form = e.target;
        const submitButton = form.querySelector("#action"); // Get the submit button
        submitButton.disabled = true; // Disable the submit button to prevent multiple clicks

        //Hide Modal
        var myModal = bootstrap.Modal.getOrCreateInstance(
          document.getElementById("modalEvent")
        );
        myModal.hide();

        // Get form input values
        const formData = new FormData(form);
        formData.append("option", option);

        try {
          const response = await fetch(
            "http://localhost/public/events/eventsCRUD",
            {
              method: "POST",
              body: formData,
            }
          );

          if (response.ok) {
            const data = await response.json();
            dataTable.ajax.reload(null, false);
          } else {
            console.error("Error:", response.statusText);
            alert("No se pudo crear la fecha.");
          }
        } catch (error) {
          console.error("Error:", error);
          alert("No se pudo crear la fecha.");
        } finally {
          submitButton.disabled = false; // Re-enable the submit button after the response is received
        }
      });

    document.addEventListener("click", function (e) {
      if (e.target && e.target.classList.contains("editBtn")) {
        opcion = 2;
        var row = e.target.closest("tr");

        var rowData = dataTable.row(row).data();

        idEvent = parseInt(rowData.id); // Access the "id" value
        nameEvent = rowData.name;
        end_event = rowData.end_date;
        ins_start_event = rowData.ins_start_date;
        ins_end_event = rowData.ins_end_date;

        var dateParts = rowData.start_date.split(" ");
        var startDay = dateParts[0];
        var startMonth = convertMonth(dateParts[1]);
        var startYear = dateParts[2];
        start_event = `${startYear}-${startMonth}-${startDay}`;

        dateParts = rowData.end_date.split(" ");
        startDay = dateParts[0];
        startMonth = convertMonth(dateParts[1]);
        startYear = dateParts[2];
        end_event = `${startYear}-${startMonth}-${startDay}`;


        dateParts = rowData.ins_start_date.split(" ");
        startDay = dateParts[0];
        startMonth = convertMonth(dateParts[1]);
        startYear = dateParts[2];
        ins_start_event = `${startYear}-${startMonth}-${startDay}`;
        
        dateParts = rowData.ins_end_date.split(" ");
        startDay = dateParts[0];
        startMonth = convertMonth(dateParts[1]);
        startYear = dateParts[2];
        ins_end_event = `${startYear}-${startMonth}-${startDay}`;

        document.getElementById("name").value = nameEvent;
        document.getElementById("start_event").value = start_event;
        document.getElementById("end_event").value = end_event;
        document.getElementById("ins_start_event").value = ins_start_event;
        document.getElementById("ins_end_event").value = ins_end_event;

        var modalTitle = document.querySelector(".modal-title");
        modalTitle.textContent = "Editar Evento";

        // Show the modal
        var modal = new bootstrap.Modal(document.getElementById("modalEvent"));
        modal.show();
      }
    });

    document.getElementById("addEvent").addEventListener("click", function () {
      option = 1;
      idEvent = null;
      document.getElementById("eventForm").reset();
      document.querySelector(".modal-title").textContent = "Agregar Evento";
    });
  });
