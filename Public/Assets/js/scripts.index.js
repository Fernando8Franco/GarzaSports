document.addEventListener("DOMContentLoaded", function () {
  const categorySelect = document.getElementById("category");
  const dependencySelect = document.getElementById("dependency");
  const sportSelect = document.getElementById("sport");
  const branchSelect = document.getElementById("branch");
  const formContainer = document.getElementById("form-container");

  const fetchPromise = fetchEventDates();
  let idEvent;
  fetchPromise.then((data) => {
    const eventName = data.name;

    document.getElementById("eventName").textContent = "Registro " + eventName;
  });

  function areSelectsSelected() {
    return (
      categorySelect.value !== "" &&
      dependencySelect.value !== "" &&
      sportSelect.value !== "" &&
      branchSelect.value != ""
    );
  }

  categorySelect.addEventListener("change", function () {
    const selectedCategory = categorySelect.value;

    const dataToSend = {
      category: selectedCategory,
    };

    const apiUrl = `${URL_PATH}/dependencies/getDependenciesByCategory`;

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
        dependencySelect.innerHTML = data;
        branchSelect.innerHTML =
          '<option value="" disabled selected>Seleccionar...</option>';
        sportSelect.innerHTML =
          '<option value="" disabled selected>Seleccionar...</option>';
        formContainer.innerHTML = "";
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
        formContainer.innerHTML = "";
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
        formContainer.innerHTML = "";
      })
      .catch((error) => {
        console.error("Error al hacer la solicitud:", error);
      });
  });

  sportSelect.addEventListener("change", function () {
    if (areSelectsSelected()) {
      const selectedSport = sportSelect.value;

      // Realizar la solicitud AJAX para cargar los deportes basados en la dependencia seleccionada
      const dataToSend = {
        id: selectedSport,
      };

      const apiUrl = `${URL_PATH}/sports/getSport`;

      // Configuración de la solicitud
      const requestOptions = {
        method: "POST",
        headers: {
          "Content-Type": "application/json", // Indicamos que estamos enviando JSON en el cuerpo
        },
        body: JSON.stringify(dataToSend), // Convertimos los datos a JSON y los enviamos en el cuerpo
      };

      fetch(apiUrl, requestOptions)
        .then((response) => response.json()) // Parseamos la respuesta JSON
        .then((data) => {
          generateDynamicForm(data);
          for (let i = 0; i < data.num_players; i++) {
            initializeCroppie(
              `player_photo_${i}`,
              `player_croppie-container_${i}`,
              `player_cropp_photo_${i}`,
              `player_cropImage_${i}`,
              `player_cropModal_${i}`,
              `player_binary_${i}`
            );
          }
          for (let i = 0; i < data.num_extraplayers; i++) {
            initializeCroppie(
              `extra_player_photo_${i}`,
              `extra_player_croppie-container_${i}`,
              `extra_player_cropp_photo_${i}`,
              `extra_player_cropImage_${i}`,
              `extra_player_cropModal_${i}`,
              `extra_player_binary_${i}`
            );
          }

          const form = document.getElementById("internForm");

          form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const formData = new FormData(form);

            try {
              const response = await fetch(`${URL_PATH}/register/internRegister`, {
                method: "POST",
                body: formData,
              });

              if (response.ok) {
                const data = await response.text();
                console.log(data);
              } else {
                console.error("Error:", response.statusText);
                alert("No se pudo completar la operación.");
              }
            } catch (error) {
              console.error("Error:", error);
              alert("No se pudo completar la operación.");
            }
          });
        })
        .catch((error) => {
          console.error("Error al hacer la solicitud:", error);
        });
    }
  });

  function generateDynamicForm(data) {
    const selectedDependency = dependencySelect.value;
    const selectedSport = sportSelect.value;
    const formContainer = document.getElementById("form-container");
    const numPlayers = data.num_players;
    const numExtraPlayers = data.num_extraplayers;
    const needCaptain = parseInt(data.has_captain);

    const form = document.createElement("form");
    form.id = "internForm";

    const formHeader = document.createElement("div");
    formHeader.className = "container";
    formHeader.innerHTML = `
      <header class="d-flex flex-wrap justify-content-center pt-5 mb-4 border-bottom border-4 border-dark">
        <span class="fw-bold text-dark event_name">FORMULARIO</span>
      </header>
    `;
    form.appendChild(formHeader);

    const teamName = document.createElement("div");
    teamName.className = "container";
    teamName.innerHTML = `
        <div class="form-floating">
          <input type="text" class="form-control mt-5 mb-3" name="team_name" id="team_name" placeholder="Nombre del Equipo" required>
          <label for="team_name">Nombre del Equipo</label>
        </div>
    `;
    form.appendChild(teamName);

    function createPlayerFields(
      isCaptain,
      isExtra,
      playerNum,
      requiredInput,
      name
    ) {
      const playerDiv = document.createElement("div");
      playerDiv.className = "container py-5 border-top border-bottom border-seconday";

      const captainHTML =
        isCaptain === 1 ? `<span class="text-danger">Capitan</span> - ` : "";

      playerDiv.innerHTML = `
        <h4 class="mb-4">${captainHTML}Jugador ${
        isExtra ? '<span class="text-info">Extra</span> ' : ""
      }no. ${playerNum + 1}</h4>
        <div class="row g-2">
          <div class="form-floating col-3">
            <input type="text" class="form-control mb-3" id="${name}_acc_number_${playerNum}" name="acc_number[]" placeholder="Número de cuenta" ${requiredInput}>
            <label for="acc_number[]">Número de cuenta</label>
          </div>
          <div class="form-floating col-3">
            <input type="text" class="form-control mb-3" id="${name}_name_${playerNum}" name="name[]" placeholder="Nombre(s)" ${requiredInput}>
            <label for="name[]">Nombre(s)</label>
          </div>
          <div class="form-floating col-3">
            <input type="text" class="form-control" id="${name}_father_last_${playerNum}" name="father_last_name[]" placeholder="Apellido Paterno" ${requiredInput}>
            <label for="father_last_name[]">Apellido Paterno</label>
          </div>
          <div class="form-floating col-3">
            <input type="text" class="form-control" id="${name}_mother_last_${playerNum}" name="mother_last_name[]" placeholder="Apellido Materno" ${requiredInput}>
            <label for="mother_last_name[]">Apellido Materno</label>
          </div>
        </div>
        <div class="row g-2">
          <div class="form-floating col-4 mb-3">
            <input type="date" class="form-control id="${name}_birthday_${playerNum}" mb-3" name="birthday[]" ${requiredInput}>
            <label for="birthday[]">Fecha de nacimiento</label>
          </div>
          <div class="form-floating col-4">
            <select class="form-select" id="${name}_gender_${playerNum}" name="gender[]" aria-label="Default select example ${requiredInput}">
              <option value="" disabled selected>Seleccionar...</option>
              <option value="Mujer">Mujer</option>
              <option value="Hombre">Hombre</option>
              <option value="Otro">Administrador</option>
            </select>
            <label for="gender[]">Sexo</label>
          </div>
          <div class="form-floating col-4">
            <input type="tel" class="form-control" id="${name}_phone_number_${playerNum}" name="phone_number[]" placeholder="Número de Celular" ${requiredInput}>
            <label for="phone_number[]">Número de Celular</label>
          </div>
        </div>
        
        <div class="row g-2">
          <div class="form-floating col-6 mb-3">
            <input type="email" class="form-control" id="${name}_email_${playerNum}" name="email[]" placeholder="Correo Electrónico" ${requiredInput}>
            <label for="email[]">Correo Electrónico</label>
          </div>
          <div class="form-floating col-3">
            <input type="number" class="form-control" id="${name}_semester_${playerNum}" name="semester[]" placeholder="Semestre" ${requiredInput}>
            <label for="semester[]">Semestre</label>
          </div>
          <div class="form-floating col-3">
            <input type="number" class="form-control" id="${name}_group_num_${playerNum}" name="group_num[]" placeholder="Grupo" ${requiredInput}>
            <label for="group_num[]">Grupo</label>
          </div>
        </div>

        <div class="row g-2 align-items-center">
          <div class="col-4 text-center">
            <img id="${name}_cropp_photo_${playerNum}" src="${URL_PATH}/assets/images/user256px.png" alt="Imagen recortada" style="max-width: 100%; width: 200px; height: 200px;">
          </div>
          <div class="col-8">
            <div class="mb-3">
              <label for="${name}_photo_${playerNum}">Imagen:</label>
              <input type="file" class="form-control" id="${name}_photo_${playerNum}" name="image" accept="image/*" ${requiredInput}>
            </div>
          </div>
        </div>

        <div class="modal fade" id="${name}_cropModal_${playerNum}" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="cropModalLabel">Recortar Imagen</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div id="${name}_croppie-container_${playerNum}"></div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                      <button type="button" class="btn btn-danger" id="${name}_cropImage_${playerNum}">Recortar</button>
                  </div>
              </div>
          </div>
        </div>

        <input type="hidden" name="photo[]" id="${name}_binary_${playerNum}">
        <input type="hidden" name="is_captain[]" value="${isCaptain}">
        <input type="hidden" name="id_dependency[]" value="${selectedDependency}">
        <input type="hidden" name="id_sport[]" value="${selectedSport}">
      `;

      form.appendChild(playerDiv);
    }

    for (let i = 0; i < numPlayers; i++) {
      const isCaptain = i === 0 && needCaptain !== 0 ? 1 : 0;
      createPlayerFields(isCaptain, false, i, "required", "player");
    }

    for (let i = 0; i < numExtraPlayers; i++) {
      createPlayerFields(0, true, i, " ", "extra_player");
    }

    const buttonDiv = document.createElement("div");
    buttonDiv.className = "container text-center mt-4";
    buttonDiv.innerHTML = `
      <button type="submit" name="action" id="action" class="btn btn-danger btn-lg">
        Enviar
      </button>
    `;
    form.appendChild(buttonDiv);

    const modal = document.createElement("div");
    modal.innerHTML = ``;

    formContainer.innerHTML = "";
    formContainer.className = "form-container";
    formContainer.appendChild(form);
  }
});

function initializeCroppie(
  inputId,
  containerId,
  croppedImageId,
  cropButtonId,
  modalCrop,
  binaryDiv
) {
  const imageInput = document.getElementById(inputId);
  const cropModalElement = document.getElementById(modalCrop);
  const croppieContainer = document.getElementById(containerId);
  let croppieInstance = null;
  let cropModal = null; // Declare the variable to hold the modal instance

  imageInput.addEventListener("change", function () {
    if (imageInput.files && imageInput.files[0]) {
      const reader = new FileReader();

      reader.onload = function (e) {
        if (croppieInstance) {
          croppieInstance.destroy();
        }
        croppieInstance = new Croppie(croppieContainer, {
          viewport: { width: 200, height: 200, type: "square" },
          boundary: { width: 300, height: 300 },
          showZoomer: true,
          mouseWheelZoom: "ctrl",
        });

        function onModalShown() {
          croppieInstance.bind({
            url: e.target.result,
          });
          cropModalElement.removeEventListener("shown.bs.modal", onModalShown);
        }

        cropModalElement.addEventListener("shown.bs.modal", onModalShown);

        cropModal = new bootstrap.Modal(cropModalElement);
        cropModal.show();
      };

      reader.readAsDataURL(imageInput.files[0]);
    }
  });

  document.getElementById(cropButtonId).addEventListener("click", function () {
    croppieInstance
      .result("blob", { format: "jpeg", size: { width: 180, height: 180 } })
      .then(function (blob) {
        const croppedImage = document.getElementById(croppedImageId);
        const binary = document.getElementById(binaryDiv);

        // Compress the image before converting it to base64
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        const img = new Image();
        img.src = URL.createObjectURL(blob);
        img.onload = function () {
          canvas.width = 200; // Set the desired width
          canvas.height = 200; // Set the desired height
          ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
          const compressedDataURL = canvas.toDataURL("image/jpeg", 0.7); // Adjust quality as needed

          binary.value = compressedDataURL.split(",")[1];
          croppedImage.src = compressedDataURL;
          cropModal.hide();
        };
      });
  });
}

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
