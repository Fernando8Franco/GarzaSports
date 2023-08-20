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
    Ene: "01",
    Feb: "02",
    Mar: "03",
    Abr: "04",
    May: "05",
    Jun: "06",
    Jul: "07",
    Ago: "08",
    Sep: "09",
    Oct: "10",
    Nov: "11",
    Dic: "12",
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

async function sendDataCRUD(data, table) {
  try {
    const response = await fetch(
      URL_PATH + "/events/" + table + "CRUD",
      {
        method: "POST",
        body: data,
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
  }
}

let dataTable;
let dataTableIsInitialized = false;

const initDataTableEvents = async (table, columns) => {
  if (dataTableIsInitialized) {
    dataTable.destroy();
  }

  try {
    var idEvent, option;
    option = 4;

    dataTable = new DataTable("#datatable_events", {
      ajax: {
        url: URL_PATH + "/events/" + table + "CRUD",
        method: "POST",
        data: { option: option },
        dataSrc: "",
      },
      columns: columns,
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
    const table = "events";
    const columns = [
      { data: "id", visible: false },
      { data: "name" },
      { data: "start_date" },
      { data: "end_date" },
      { data: "ins_start_date" },
      { data: "ins_end_date" },
      {
        defaultContent:
          "<div class='text-center'><button class='btn btn-primary btn-sm editBtn' data-bs-toggle='modal' data-bs-target='#modalEvent'>Editar  <i class='fa-solid fa-pen-to-square'></i></button><button class='btn btn-danger btn-sm deleteBtn'>Eliminar  <i class='fa-regular fa-trash-can'></i></button></div>",
      },
    ];
    await initDataTableEvents(table, columns);

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

        sendDataCRUD(formData, table);
        submitButton.disabled = false;
      });

    document.getElementById("addEvent").addEventListener("click", function () {
      option = 1;
      idEvent = null;
      document.getElementById("eventForm").reset();
      document.querySelector(".modal-title").textContent = "Agregar Evento";
      document.getElementById("action").innerHTML =
        "Agregar <i class='far fa-calendar-check'></i>";
    });

    document.addEventListener("click", function (e) {
      if (e.target && e.target.classList.contains("editBtn")) {
        option = 2;
        var row = e.target.closest("tr");
        var rowData = dataTable.row(row).data();

        const formatDate = (dateString) => {
          const [day, monthStr, year] = dateString.split(" ");
          const month = convertMonth(monthStr);
          return `${year}-${month}-${day}`;
        };

        idEvent = parseInt(rowData.id); // Access the "id" value
        nameEvent = rowData.name;
        start_event = formatDate(rowData.start_date);
        end_event = formatDate(rowData.end_date);
        ins_start_event = formatDate(rowData.ins_start_date);
        ins_end_event = formatDate(rowData.ins_end_date);

        document.getElementById("idEvent").value = idEvent;
        document.getElementById("name").value = nameEvent;
        document.getElementById("start_event").value = start_event;
        document.getElementById("end_event").value = end_event;
        document.getElementById("ins_start_event").value = ins_start_event;
        document.getElementById("ins_end_event").value = ins_end_event;

        document.querySelector(".modal-title").textContent = "Editar Evento";
        document.getElementById("action").innerHTML =
          "Editar <i class='far fa-calendar-check'></i>";
      }
    });

    document.addEventListener("click", function (e) {
      if (e.target && e.target.classList.contains("deleteBtn")) {
        option = 3;

        var row = e.target.closest("tr");
        var rowData = dataTable.row(row).data();
        idEvent = parseInt(rowData.id);

        const formDataDelete = new FormData();
        formDataDelete.append("idEvent", idEvent);
        formDataDelete.append("option", option);

        var answer = confirm(
          '¿Está seguro de borrar el evento "' + rowData.name + '"?'
        );

        if (answer) {
          sendDataCRUD(formDataDelete, table);
        }
      }
    });
  });

  document
  .getElementById("users_link")
  .addEventListener("htmx:afterRequest", async function () {
    const table = "events";
    const columns = [
      { data: "id", visible: false },
      { data: "name" },
      { data: "start_date" },
      { data: "end_date" },
      { data: "ins_start_date" },
      { data: "ins_end_date" },
      {
        defaultContent:
          "<div class='text-center'><button class='btn btn-primary btn-sm editBtn' data-bs-toggle='modal' data-bs-target='#modalEvent'>Editar  <i class='fa-solid fa-pen-to-square'></i></button><button class='btn btn-danger btn-sm deleteBtn'>Eliminar  <i class='fa-regular fa-trash-can'></i></button></div>",
      },
    ];
    await initDataTableEvents(table, columns);

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

        sendDataCRUD(formData, table);
        submitButton.disabled = false;
      });

    document.getElementById("addEvent").addEventListener("click", function () {
      option = 1;
      idEvent = null;
      document.getElementById("eventForm").reset();
      document.querySelector(".modal-title").textContent = "Agregar Evento";
      document.getElementById("action").innerHTML =
        "Agregar <i class='far fa-calendar-check'></i>";
    });

    document.addEventListener("click", function (e) {
      if (e.target && e.target.classList.contains("editBtn")) {
        option = 2;
        var row = e.target.closest("tr");
        var rowData = dataTable.row(row).data();

        const formatDate = (dateString) => {
          const [day, monthStr, year] = dateString.split(" ");
          const month = convertMonth(monthStr);
          return `${year}-${month}-${day}`;
        };

        idEvent = parseInt(rowData.id); // Access the "id" value
        nameEvent = rowData.name;
        start_event = formatDate(rowData.start_date);
        end_event = formatDate(rowData.end_date);
        ins_start_event = formatDate(rowData.ins_start_date);
        ins_end_event = formatDate(rowData.ins_end_date);

        document.getElementById("idEvent").value = idEvent;
        document.getElementById("name").value = nameEvent;
        document.getElementById("start_event").value = start_event;
        document.getElementById("end_event").value = end_event;
        document.getElementById("ins_start_event").value = ins_start_event;
        document.getElementById("ins_end_event").value = ins_end_event;

        document.querySelector(".modal-title").textContent = "Editar Evento";
        document.getElementById("action").innerHTML =
          "Editar <i class='far fa-calendar-check'></i>";
      }
    });

    document.addEventListener("click", function (e) {
      if (e.target && e.target.classList.contains("deleteBtn")) {
        option = 3;

        var row = e.target.closest("tr");
        var rowData = dataTable.row(row).data();
        idEvent = parseInt(rowData.id);

        const formDataDelete = new FormData();
        formDataDelete.append("idEvent", idEvent);
        formDataDelete.append("option", option);

        var answer = confirm(
          '¿Está seguro de borrar el evento "' + rowData.name + '"?'
        );

        if (answer) {
          sendDataCRUD(formDataDelete, table);
        }
      }
    });
  });