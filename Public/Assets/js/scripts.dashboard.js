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
      extend: "excel",
      className: "btn btn-success",
      text: "<i class='fa-solid fa-file-csv'></i> Excel",
      title: "Registros",
      exportOptions: {
        stripHtml: true,
        columns: [2, 3, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
      },
    },
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

const initDataTable = async (table, columns, id_dependency) => {
  try {
    if (dataTableIsInitialized) {
      dataTable.destroy();
    }

    var option;
    option = 4;

    if (table != "register") {
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
        lengthMenu: [
          [10, 25, 50, 250, -1],
          [10, 25, 50, 250, "Todos"],
        ],
      });
    } else {
      dataTable = new DataTable("#datatable", {
        deferRender: true,
        ...dataTableConfig,
        paging: false,
        ajax: {
          url: URL_PATH + "/" + table + "/" + table + "CRUD",
          method: "POST",
          data: { option: option, id_dependency: id_dependency},
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
    }

    dataTableIsInitialized = true;
  } catch (error) {
    console.error("Error:", error);
  }
};

function isBeforeDate(date1, date2) {
  return new Date(date1) <= new Date(date2);
}

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

async function fetchGetDependencies() {
  const url = `${URL_PATH}/dependencies/getDependenciesWithAll`;

  return fetch(url, {
    method: "GET",
    dataType: "json",
  })
    .then((response) => response.text())
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
      if (TABLE == "register") {
        const dependencySelect = document.getElementById("dependency");
        const dataTable = document.getElementById("tableContainer");

        const fetchPromiseGetDependencies = fetchGetDependencies();
        fetchPromiseGetDependencies.then((data) => {
          dependencySelect.innerHTML = data;
        });

        dependencySelect.addEventListener("change", async function () {
          await initDataTable(TABLE, COLUMNS, dependencySelect.value);
          const form = document.getElementById(FORM_NAME);
          const submitButton = form.querySelector("#action");
          dataTable.style.display = "block";

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

          document
            .getElementById(BTN_ID)
            .addEventListener("click", function () {
              if (BTN_ID == "addEmployee") {
                document.getElementById("password").type = "password";
                document.querySelector('label[for="password"]').className = "";
                document.getElementById("div_on_employee").className =
                  "form-floating col-6";
                document.getElementById("div_acc_pass").className = "row g-2";
              }
              document.querySelector(".modal-title").textContent =
                MODAL_TITLE_ADD;
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
              document.querySelector(".modal-title").textContent =
                MODAL_TITLE_EDIT;
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
      } else {
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
            document.querySelector(".modal-title").textContent =
              MODAL_TITLE_EDIT;
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
      }
    });
};

createCrudTable(
  "events",
  "eventsCRUD",
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
  "dependenciesCRUD",
  [
    { data: "id", visible: false },
    { data: "name" },
    { data: "category"},
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
  "employeesCRUD",
  [
    { data: "id", visible: false },
    { data: "id_dependency", visible: false },
    { data: "no_employee" },
    { data: "dependency_name" },
    { data: "name_s" },
    {
      data: null,
      render: function (data, type, full, meta) {
        return full.father_last_name + " " + full.mother_last_name;
      },
    },
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

createCrudTable(
  "sports",
  "sportsCRUD",
  [
    { data: "id", visible: false },
    { data: "name" },
    { data: "type" },
    { data: "gender" },
    { data: "num_players", className: "text-center" },
    { data: "num_extraplayers", className: "text-center" },
    { data: "has_captain", className: "text-center" },
    {
      defaultContent:
        "<div class='text-center'><button class='btn btn-primary btn-sm editBtnSport' data-bs-toggle='modal' data-bs-target='#modalSport'>Editar  <i class='fa-solid fa-pen-to-square'></i></button><button class='btn btn-danger btn-sm deleteBtnSport'>Eliminar  <i class='fa-regular fa-trash-can'></i></button></div>",
    },
  ],
  "sportForm",
  "modalSport",
  "addSport",
  "Agregar Deporte",
  "Editar Deporte",
  "editBtnSport",
  "deleteBtnSport",
  (rowData) => {
    var id = parseInt(rowData.id);
    var name = rowData.name;
    var type = rowData.type;
    var gender = rowData.gender;
    var num_players = rowData.num_players;
    var num_extraplayers = rowData.num_extraplayers;
    var has_captain = rowData.has_captain;

    document.getElementById("id").value = id;
    document.getElementById("name").value = name;
    document.getElementById("type").value = type;
    document.getElementById("gender").value = gender;
    document.getElementById("num_players").value = num_players;
    document.getElementById("num_extraplayers").value = num_extraplayers;
    document.getElementById("has_captain").value = formatStatus(has_captain);
  }
);

createCrudTable(
  "teams",
  "teamsCRUD",
  [
    { data: "id", visible: false },
    { data: "name" },
    { data: "record_date" },
    { data: "dependency_name" },
    { data: "sport_name" },
    { data: "event_name" },
    {
      defaultContent:
        "<div class='text-center'><button class='btn btn-primary btn-sm editBtnTeam' data-bs-toggle='modal' data-bs-target='#modalTeam'>Editar  <i class='fa-solid fa-pen-to-square'></i></button><button class='btn btn-danger btn-sm deleteBtnTeam'>Eliminar  <i class='fa-regular fa-trash-can'></i></button></div>",
    },
  ],
  "teamForm",
  "modalTeam",
  "addTeam",
  " ",
  "Editar Equipo",
  "editBtnTeam",
  "deleteBtnTeam",
  (rowData) => {
    var id = parseInt(rowData.id);
    var name = rowData.name;
    var record_date = rowData.record_date;
    var dependency_name = rowData.dependency_name;
    var sport_name = rowData.sport_name;
    var event_name = rowData.event_name;

    document.getElementById("id").value = id;
    document.getElementById("name").value = name;
    document.getElementById("record_date").value = record_date;
    document.getElementById("dependency_name").value = dependency_name;
    document.getElementById("sport_name").value = sport_name;
    document.getElementById("event_name").value = event_name;
  }
);

createCrudTable(
  "register",
  "registerCRUD",
  [
    { data: "Player_ID", visible: false },
    {
      data: null,
      render: function (data, type, full, meta) {
        return full.Team_Name + "<br>" + full.Record_Date;
      },
    },
    {
      data: null,
      render: function (data, type, full, meta) {
        return full.Dependency_Name + " - " + full.Dependency_Category;
      },
    },
    {
      data: null,
      render: function (data, type, full, meta) {
        return full.Sport_Name + " - " + full.Sport_Gender;
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
    { data: "Record_Date", visible: false },
    { data: "Team_Name", visible: false },
    { data: "Player_Account_Number", visible: false },
    { data: "Player_Semester", visible: false },
    { data: "Player_Group_Number", visible: false },
    { data: "Player_Father_Last_Name", visible: false },
    { data: "Player_Mother_Last_Name", visible: false },
    { data: "Player_Name", visible: false },
    { data: "Player_Birthday", visible: false },
    { data: "Player_Gender", visible: false },
    { data: "Player_Is_Captain", visible: false },
    { data: "Player_Phone_Number", visible: false },
    { data: "Player_Email", visible: false },
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
