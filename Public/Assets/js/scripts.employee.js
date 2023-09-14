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

const dataTableConfig = {
  dom: "lBfrtip",
  buttons: [
    {
      extend: "print",
      className: "btn btn-danger text-white",
      text: "<i class='fa-solid fa-print'></i> Imprimir",
      title: "Registros",
      exportOptions: {
        stripHtml: false,
        columns: [1, 2, 3, 4, 5],
      },
      customize: function (win) {
        var table = $(win.document.body)
          .find("table")
          .addClass("display")
          .css("font-size", "10px");

        // Asegúrate de que los estilos de ancho de columna se apliquen en todas las páginas.
        table.find("tr").find("td:eq(0)").css("width", "75px");
        table.find("tr").find("td:eq(1)").css("width", "50px");
        table.find("tr").find("td:eq(2)").css("width", "80px");
        table.find("tr").find("td:eq(4)").css("width", "20px");

        var rows = table.find("tbody tr");
        var firstPage = true;
        var registerLength = 8;

        // Divide las filas en grupos de 8 y agrega un salto de página después de cada grupo.
        for (var i = registerLength; i < rows.length; i += registerLength) {
          rows
            .eq(i)
            .before(
              '<tr class="paginate_break"><td colspan="5">&nbsp;</td></tr>'
            );
          if (firstPage) {
            registerLength = 9;
            firstPage = false;
          }
        }
      },
    },
  ],
};

const initDataTable = async (table, columns, crudname) => {
  try {
    if (dataTableIsInitialized) {
      dataTable.destroy();
    }

    var option;
    option = 4;
    id_dependency = ID_DEPENDENCY;

    dataTable = new DataTable("#datatable", {
      ...dataTableConfig,
      paging: false,
      ajax: {
        url: URL_PATH + "/" + table + "/" + crudname,
        method: "POST",
        data: { option: option, id_dependency: id_dependency },
        dataSrc: "",
      },
      columns: columns,
      order: [[0, "asc"]],
      columnDefs: [
        {
          targets: 1, // Target the "name" column (index 1)
          orderData: [0], // Use data from the "id" column (index 0) for ordering
        },
        { width: "100px", targets: 1 },
        { width: "150px", targets: 2 },
        { width: "100px", targets: 3 },
      ],
      language: dataTableLanguage,
      lengthMenu: [[-1], ["Todos"]],
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

async function fetchRegisters() {
  const url = `${URL_PATH}/register/registersData`;

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
    const fetchPromiseEvent = fetchEventDates();
    const fetchPromiseRegisters = fetchRegisters();

    fetchPromiseEvent.then((data) => {
      document.getElementById("eventName").textContent = data.name;
      document.getElementById("dates").textContent =
        data.start_date + " - " + data.end_date;
    });
    fetchPromiseRegisters.then((data) => {
      document.getElementById("registers").textContent = data.rows_team;
      document.getElementById("num_players").textContent = data.rows_player;
      document.getElementById("num_teams").textContent = data.rows_team;
    });
  });

document
  .getElementById("targetPHP")
  .addEventListener("htmx:beforeOnLoad", () => {
    const fetchPromiseEvent = fetchEventDates();
    const fetchPromiseRegisters = fetchRegisters();

    fetchPromiseEvent.then((data) => {
      const start_date = data.start_date;
      const end_date = data.end_date;
      const eventName = data.name;

      document.getElementById("eventName").textContent = eventName;
      document.getElementById("dates").textContent =
        start_date + " - " + end_date;
    });
    fetchPromiseRegisters.then((data) => {
      document.getElementById("registers").textContent = data.rows_team;
      document.getElementById("num_players").textContent = data.rows_player;
      document.getElementById("num_teams").textContent = data.rows_team;
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
  CRUDNAME,
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
      if (TABLE == "registerByDependency") {
        TABLE = "register";
      }
      await initDataTable(TABLE, COLUMNS, CRUDNAME);

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
          const response = await fetch(`${URL_PATH}/${TABLE}/${CRUDNAME}`, {
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
        if (BTN_ID == "addEmployee") {
          document.getElementById("password").type = "password";
          document.querySelector('label[for="password"]').className = "";
          document.getElementById("div_on_employee").className =
            "form-floating col-6";
          document.getElementById("div_acc_pass").className = "row g-2";
        }
        document.querySelector(".modal-title").textContent = MODAL_TITLE_ADD;
        document.getElementById("action").textContent = "Agregar";

        option = 1;
        idEvent = null;
        form.reset();
      });

      document.addEventListener("click", function (e) {
        if (e.target && e.target.classList.contains(BTN_EDIT)) {
          if (BTN_EDIT == "editBtnEmployee") {
            document.getElementById("password").type = "hidden";
            document.querySelector('label[for="password"]').className =
              "d-none";
            document.getElementById("div_on_employee").className =
              "form-floating col-12";
            document.getElementById("div_acc_pass").className = "";
          }
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

          Swal.fire({
            title: "¿Está seguro?",
            text: "No podra volver a recuperar esta infomación",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, estoy seguro!",
          }).then(async (result) => {
            if (result.isConfirmed) {
              const formDataDelete = new FormData();
              formDataDelete.append("id", id);
              formDataDelete.append("option", option);
              try {
                const response = await fetch(
                  `${URL_PATH}/${TABLE}/${CRUDNAME}`,
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
              Swal.fire(
                "¡Eliminado!",
                "Elemento eliminado correctamente.",
                "success"
              );
            }
          });
        }
      });
    });
};

createCrudTable(
  "registerByDependency",
  "registerByDependencyCRUD",
  [
    { data: "Player_ID", visible: false },
    {
      data: null,
      render: function (data, type, full, meta) {
        return full.Team_Name + "<br>" + full.Record_Date;
      },
    },
    { data: "Dependency_Name" },
    {
      data: null,
      render: function (data, type, full, meta) {
        return full.Sport_Name + "<br>" + "Rama: " + full.Sport_Gender;
      },
    },
    {
      data: null,
      render: function (data, type, full, meta) {
        return (
          "No. cuenta: " +
          full.Player_Account_Number +
          " &nbsp&nbsp Semestre y Grupo: " +
          full.Player_Semester +
          " - " +
          full.Player_Group_Number +
          "<br>" +
          "Apellido paterno: " +
          full.Player_Father_Last_Name +
          "<br>" +
          "Apellido materno: " +
          full.Player_Mother_Last_Name +
          "<br>" +
          "Nombre: " +
          full.Player_Name +
          "<br>" +
          "Edad: " +
          full.Player_Birthday +
          " &nbsp&nbsp " +
          "Sexo: " +
          full.Player_Gender +
          " &nbsp&nbsp " +
          "Rol: " +
          full.Player_Is_Captain +
          "<br>" +
          "Número: " +
          full.Player_Phone_Number +
          "<br>" +
          "Correo: " +
          full.Player_Email
        );
      },
    },
    {
      data: null,
      render: function (data, type, full, meta) {
        return (
          '<img src="data:image/png;base64,' +
          full.Player_Photo +
          '" alt="Player Photo" class="mx-auto d-block" width="75" height="75"/>'
        );
      },
    },
  ],
  "registerForm",
  "modalRegister",
  "addRegister",
  " ",
  " ",
  "editRegister",
  "deleteRegister",
  (rowData) => {}
);
