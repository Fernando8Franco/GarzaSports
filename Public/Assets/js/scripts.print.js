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
      searching: false,
      data: dataTeam,
      columns: [
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
      language: {
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
      },
      lengthMenu: [[-1], ["Todos"]],
      dom: "lBfrtip",
      buttons: [
        {
          extend: "print",
          className: "btn btn-info text-white",
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
    dataTable.buttons().trigger();
  } catch (error) {
    console.error("Error:", error);
  }
};
