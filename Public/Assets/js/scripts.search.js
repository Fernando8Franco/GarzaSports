document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("searchForm");
  const dataTableContainer = document.getElementById("container");
  function areSelectsSelected() {
    return (
      categorySelect.value !== "" &&
      dependencySelect.value !== "" &&
      sportSelect.value !== "" &&
      branchSelect.value != ""
    );
  }

  const categorySelect = document.getElementById("category");
  const dependencySelect = document.getElementById("dependency");
  const sportSelect = document.getElementById("sport");
  const branchSelect = document.getElementById("branch");

  const fetchPromiseGetDependencies = fetchGetCategories();
  fetchPromiseGetDependencies.then((data) => {
    categorySelect.innerHTML = data;
    dependencySelect.innerHTML =
      '<option value="" disabled selected>Seleccionar...</option>';
    branchSelect.innerHTML =
      '<option value="" disabled selected>Seleccionar...</option>';
    sportSelect.innerHTML =
      '<option value="" disabled selected>Seleccionar...</option>';
    dataTableContainer.style.display = "none";
  });

  categorySelect.addEventListener("change", function () {
    const selectedCategory = categorySelect.value;

    const dataToSend = {
      category: selectedCategory,
    };

    const apiUrl = `${URL_PATH}/dependencies/getDependenciesByCategory`;

    const requestOptions = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(dataToSend),
    };

    fetch(apiUrl, requestOptions)
      .then((response) => response.text()) // Parseamos la respuesta JSON
      .then((data) => {
        dependencySelect.innerHTML = data;
        branchSelect.innerHTML =
          '<option value="" disabled selected>Seleccionar...</option>';
        sportSelect.innerHTML =
          '<option value="" disabled selected>Seleccionar...</option>';
        dataTableContainer.style.display = "none";
      })
      .catch((error) => {
        console.error("Error al hacer la solicitud:", error);
      });
  });

  dependencySelect.addEventListener("change", function () {
    const selectedDependency = dependencySelect.value;

    // Realizar la solicitud AJAX para cargar los deportes basados en la dependencia seleccionada
    const dataToSend = {
      dependency: selectedDependency,
    };

    const apiUrl = `${URL_PATH}/dependencies/getbranches`;

    // Configuración de la solicitud
    const requestOptions = {
      method: "POST",
      headers: {
        "Content-Type": "application/json", // Indicamos que estamos enviando JSON en el cuerpo
      },
      body: JSON.stringify(dataToSend), // Convertimos los datos a JSON y los enviamos en el cuerpo
    };

    fetch(apiUrl, requestOptions)
      .then((response) => response.text()) // Parseamos la respuesta JSON
      .then((data) => {
        branchSelect.innerHTML = data;
        sportSelect.innerHTML =
          '<option value="" disabled selected>Seleccionar...</option>';
        dataTableContainer.style.display = "none";
      })
      .catch((error) => {
        console.error("Error al hacer la solicitud:", error);
      });
  });

  branchSelect.addEventListener("change", function () {
    const selectedDependency = dependencySelect.value;
    const selectedBranch = branchSelect.value;

    // Realizar la solicitud AJAX para cargar los deportes basados en la dependencia seleccionada
    const dataToSend = {
      gender: selectedBranch,
      dependency: selectedDependency,
    };

    const apiUrl = `${URL_PATH}/dependencies/getSportsByDependency`;

    // Configuración de la solicitud
    const requestOptions = {
      method: "POST",
      headers: {
        "Content-Type": "application/json", // Indicamos que estamos enviando JSON en el cuerpo
      },
      body: JSON.stringify(dataToSend), // Convertimos los datos a JSON y los enviamos en el cuerpo
    };

    fetch(apiUrl, requestOptions)
      .then((response) => response.text()) // Parseamos la respuesta JSON
      .then((data) => {
        sportSelect.innerHTML = data;
        dataTableContainer.style.display = "none";
      })
      .catch((error) => {
        console.error("Error al hacer la solicitud:", error);
      });
  });

  sportSelect.addEventListener("change", function () {
    if (areSelectsSelected()) {
      const selectedSport = sportSelect.value;

      form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        try {
          const response = await fetch(`${URL_PATH}/teams/getTeam`, {
            method: "POST",
            body: formData,
          });

          if (response.ok) {
            const data = await response.json();
            if (data[0]["Player_ID"] == "NO REGISTRADO") {
              Swal.fire({
                icon: "error",
                title: "No se encontro el registro",
                text: "El número de cuenta o correo no se encuentran en los registros",
              });
            } else {
              dataTableContainer.style.display = "block";
              initDataTable(data);
            }
          } else {
            console.error("Error:", response.statusText);
            alert("No se pudo completar la operación.");
          }
        } catch (error) {
          console.error("Error:", error);
          alert("No se pudo completar la operación.");
        }
      });
    }
  });
});

async function fetchGetDependencies() {
  const url = `${URL_PATH}/dependencies/getDependencies`;

  return fetch(url, {
    method: "GET",
    dataType: "json",
  })
    .then((response) => response.text())
    .catch((error) => {
      console.error("Error:", error);
    });
}

async function fetchGetCategories() {
  const url = `${URL_PATH}/dependencies/getCategories`;

  return fetch(url, {
    method: "GET",
    dataType: "json",
  })
    .then((response) => response.text())
    .catch((error) => {
      console.error("Error:", error);
    });
}

let dataTable;
let dataTableIsInitialized = false;

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

function initDataTable(dataTeam) {
  try {
    if (dataTableIsInitialized) {
      dataTable.destroy();
    }

    dataTable = new DataTable("#datatable", {
      paging: false,
      info: false,
      data: dataTeam,
      columns: [
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
      dom: "lBfrtip",
      buttons: [
        {
          extend: "print",
          className: "btn btn-danger text-white",
          text: "<i class='fa-solid fa-print'></i> Imprimir",
          title: "Registro",
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
    });

    dataTableIsInitialized = true;
  } catch (error) {
    console.error("Error:", error);
  }
}
