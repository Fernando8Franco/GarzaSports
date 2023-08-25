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
  const fetchPromise = fetchEventDates();

  fetchPromise.then((data) => {
    const start_date = data.start_date;
    const end_date = data.end_date;
    const eventName = data.name;

    // Perform DOM updates only once
    document.getElementById("dates").innerHTML =
      start_date + " - " + end_date;
    document.getElementById("eventName").innerHTML = eventName;
  });
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

const formatDate = (dateString) => {
  const [day, monthStr, year] = dateString.split(" ");
  const month = convertMonth(monthStr);
  return `${year}-${month}-${day}`;
};

const initDataTable = async (table, columns) => {
  try {
    if (dataTableIsInitialized) {
      dataTable.destroy();
    }

    var option;
    option = 4;

    dataTable = new DataTable("#datatable", {
      ajax: {
        url: URL_PATH + "/" + table + "/" + table + "CRUD",
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

function isBeforeDate(date1, date2) {
  return new Date(date1) <= new Date(date2);
}

// Function to validate the form
function validateForm() {
  const startEvent = document.getElementById("start_event").value;
  const endEvent = document.getElementById("end_event").value;
  const insStartEvent = document.getElementById("ins_start_event").value;
  const insEndEvent = document.getElementById("ins_end_event").value;

  if (!isBeforeDate(startEvent, endEvent)) {
    alert(
      "La fecha de inicio del evento debe ser anterior a la fecha de finalización del evento."
    );
    return false;
  }

  if (!isBeforeDate(insStartEvent, insEndEvent)) {
    alert(
      "La fecha de inicio de inscripción debe ser anterior a la fecha de finalización de inscripción."
    );
    return false;
  }

  if (
    !isBeforeDate(startEvent, insStartEvent) ||
    !isBeforeDate(insEndEvent, endEvent)
  ) {
    alert(
      "Las fechas de inscripción deben estar dentro del rango de fechas del evento."
    );
    return false;
  }

  return true;
}

const formatStatus = (status) => {
  if (status == '<i class="fa-solid fa-xmark"></i>') {
    return 0;
  } else if (status == '<i class="fa-solid fa-check"></i>') {
    return 1;
  }
};

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

async function fetchEventDates() {
  const url = `${URL_PATH}/events/eventsDates`;

  return fetch(url, {
    method: "GET",
    dataType: "json",
  })
    .then((response) => response.json())
    .catch((error) => {
      console.error("Error:", error);
    });
}

document
  .getElementById("dashboard_link")
  .addEventListener("htmx:beforeOnLoad", () => {
    const fetchPromise = fetchEventDates();

    fetchPromise.then((data) => {
      const start_date = data.start_date;
      const end_date = data.end_date;
      const eventName = data.name;

      document.getElementById("dates").innerHTML =
        start_date + " - " + end_date;
      document.getElementById("eventName").innerHTML = eventName;
    });
  });

let dataTable;
let dataTableIsInitialized = false;

////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////
//
// FUNCTION FOR CREATE THE DATATABLE
//
////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////

const createCrudTable = (
  TABLE,
  COLUMNS,
  FORM_NAME,
  MODAL_NAME,
  BTN_ID,
  MODAL_TITLE_ADD,
  MODAL_TITLE_EDIT,
  BTN_EDIT,
  BTN_DELETE,
  formDataCallback
) => {
  document
    .getElementById(`${TABLE}_link`)
    .addEventListener("htmx:afterOnLoad", async function () {
      await initDataTable(TABLE, COLUMNS);

      const form = document.getElementById(FORM_NAME);
      const submitButton = form.querySelector("#action");

      const hideModal = () => {
        const myModal = bootstrap.Modal.getOrCreateInstance(
          document.getElementById(MODAL_NAME)
        );
        myModal.hide();
      };

      form.addEventListener("submit", async (e) => {
        e.preventDefault();

        if (TABLE == "events" && !validateForm()) {
          return;
        }

        submitButton.disabled = true;
        hideModal();

        const formData = new FormData(form);
        formData.append("option", option);

        try {
          const response = await fetch(`${URL_PATH}/${TABLE}/${TABLE}CRUD`, {
            method: "POST",
            body: formData,
          });

          if (response.ok) {
            const data = await response.json();
            dataTable.ajax.reload(null, false);
          } else {
            console.error("Error:", response.statusText);
            alert("No se pudo completar la operación.");
          }
        } catch (error) {
          console.error("Error:", error);
          alert("No se pudo completar la operación.");
        }

        submitButton.disabled = false;
      });

      document.getElementById(BTN_ID).addEventListener("click", function () {
        document.querySelector(".modal-title").textContent = MODAL_TITLE_ADD;
        document.getElementById("action").textContent = "Agregar";

        option = 1;
        idEvent = null;
        form.reset();
      });

      document.addEventListener("click", function (e) {
        if (e.target && e.target.classList.contains(BTN_EDIT)) {
          document.querySelector(".modal-title").textContent = MODAL_TITLE_EDIT;
          document.getElementById("action").innerHTML = "Editar";

          option = 2;
          const row = e.target.closest("tr");
          const rowData = dataTable.row(row).data();
          formDataCallback(rowData);
        }
      });

      document.addEventListener("click", async function (e) {
        if (e.target && e.target.classList.contains(BTN_DELETE)) {
          option = 3;

          const row = e.target.closest("tr");
          const rowData = dataTable.row(row).data();
          const id = parseInt(rowData.id);

          if (!rowData.toBeDeleted) {
            rowData.toBeDeleted = true;
            const confirmMessage = `¿Está seguro de borrar el evento "${rowData.name}"?`;
            const answer = confirm(confirmMessage);

            if (answer) {
              const formDataDelete = new FormData();
              formDataDelete.append("id", id);
              formDataDelete.append("option", option);

              try {
                const response = await fetch(
                  `${URL_PATH}/${TABLE}/${TABLE}CRUD`,
                  {
                    method: "POST",
                    body: formDataDelete,
                  }
                );

                if (response.ok) {
                  const data = await response.json();
                  dataTable.ajax.reload(null, false);
                } else {
                  console.error("Error:", response.statusText);
                  alert("No se pudo completar la operación.");
                }
              } catch (error) {
                console.error("Error:", error);
                alert("No se pudo completar la operación.");
              }
            } else {
              setTimeout(function () {
                rowData.toBeDeleted = false;
              }, 500);
            }
          }
        }
      });
    });
};

createCrudTable(
  "events",
  [
    { data: "id", visible: false },
    { data: "name" },
    { data: "start_date" },
    { data: "end_date" },
    { data: "ins_start_date" },
    { data: "ins_end_date" },
    {
      defaultContent:
        "<div class='text-center'><button class='btn btn-primary btn-sm editBtnEvent' data-bs-toggle='modal' data-bs-target='#modalEvent'>Editar  <i class='fa-solid fa-pen-to-square'></i></button><button class='btn btn-danger btn-sm deleteBtnEvent'>Eliminar  <i class='fa-regular fa-trash-can'></i></button></div>",
    },
  ],
  "eventForm",
  "modalEvent",
  "addEvent",
  "Agregar Evento",
  "Editar Evento",
  "editBtnEvent",
  "deleteBtnEvent",
  (rowData) => {
    const idEvent = parseInt(rowData.id);
    const nameEvent = rowData.name;
    const start_event = formatDate(rowData.start_date);
    const end_event = formatDate(rowData.end_date);
    const ins_start_event = formatDate(rowData.ins_start_date);
    const ins_end_event = formatDate(rowData.ins_end_date);

    document.getElementById("id").value = idEvent;
    document.getElementById("name").value = nameEvent;
    document.getElementById("start_event").value = start_event;
    document.getElementById("end_event").value = end_event;
    document.getElementById("ins_start_event").value = ins_start_event;
    document.getElementById("ins_end_event").value = ins_end_event;
  }
);

createCrudTable(
  "dependencies",
  [
    { data: "id", visible: false },
    { data: "name" },
    { data: "category" },
    {
      defaultContent:
        "<div class='text-center'><button class='btn btn-primary btn-sm editBtnDependency' data-bs-toggle='modal' data-bs-target='#modalDependency'>Editar  <i class='fa-solid fa-pen-to-square'></i></button><button class='btn btn-danger btn-sm deleteBtnDependency'>Eliminar  <i class='fa-regular fa-trash-can'></i></button></div>",
    },
  ],
  "dependencyForm",
  "modalDependency",
  "addDependency",
  "Agregar Dependencia",
  "Editar Depedencia",
  "editBtnDependency",
  "deleteBtnDependency",
  (rowData) => {
    var id = parseInt(rowData.id);
    var name = rowData.name;
    var category = rowData.category;

    document.getElementById("id").value = id;
    document.getElementById("name").value = name;
    document.getElementById("category").value = category;
  }
);

createCrudTable(
  "employees",
  [
    { data: "id", visible: false },
    { data: "id_dependency", visible: false },
    { data: "no_employee" },
    { data: "dependency_name" },
    { data: "name_s" },
    { data: "father_last_name" },
    { data: "mother_last_name" },
    { data: "role_emp" },
    { data: "is_active", className: "text-center" },
    {
      defaultContent:
        "<div class='text-center'><button class='btn btn-primary btn-sm editBtnEmployee' data-bs-toggle='modal' data-bs-target='#modalEmployee'>Editar  <i class='fa-solid fa-pen-to-square'></i></button><button class='btn btn-danger btn-sm deleteBtnEmployee'>Eliminar  <i class='fa-regular fa-trash-can'></i></button></div>",
    },
  ],
  "employeeForm",
  "modalEmployee",
  "addEmployee",
  "Agregar Usuario",
  "Editar Usuario",
  "editBtnEmployee",
  "deleteBtnEmployee",
  (rowData) => {
    var id = parseInt(rowData.id);
    var no_employee = rowData.no_employee;
    var id_dependency = rowData.id_dependency;
    var name_s = rowData.name_s;
    var father_last_name = rowData.father_last_name;
    var mother_last_name = rowData.mother_last_name;
    var role_emp = rowData.role_emp;
    var is_active = rowData.is_active;

    document.getElementById("id").value = id;
    document.getElementById("no_employee").value = no_employee;
    document.getElementById("dependency_name").value = id_dependency;
    document.getElementById("name_s").value = name_s;
    document.getElementById("father_last_name").value = father_last_name;
    document.getElementById("mother_last_name").value = mother_last_name;
    document.getElementById("role_emp").value = role_emp;
    document.getElementById("is_active").value = formatStatus(is_active);
  }
);
