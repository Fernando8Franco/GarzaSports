document.addEventListener("DOMContentLoaded", function () {
  const categorySelect = document.getElementById("category");
  const dependencySelect = document.getElementById("dependency");
  const sportSelect = document.getElementById("sport");
  const branchSelect = document.getElementById("branch");

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
        })
        .catch((error) => {
          console.error("Error al hacer la solicitud:", error);
        });
    }
  });

  function generateDynamicForm(data) {
    const selectedDependency = dependencySelect.value;
    const formContainer = document.getElementById("form-container");
    const numPlayers = data.num_players;
    const numExtraPlayers = data.num_extraplayers;
    const needCaptain = data.has_captain;

    const form = document.createElement("form");

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

    // Generar campos para el equipo
    for (let i = 0; i < numPlayers; i++) {
      const playerDiv = document.createElement("div");
      playerDiv.className = "container py-3";
      if (i == 0 && needCaptain) {
        playerDiv.innerHTML = `
        <h4 class="mb-4"><span class="text-danger">Capitan</span> - Jugador no. ${i + 1}</h4>
        <div class="row g-2">
          <div class="form-floating col-4">
            <input type="text" class="form-control mb-3" name="player_name_${i}" placeholder="Nombre(s)" required>
            <label for="player_name_${i}">Nombre(s)</label>
          </div>
          <div class="form-floating col-4">
            <input type="text" class="form-control" name="player_father_last_name_${i}" placeholder="Apellido Paterno" required>
            <label for="player_father_last_name_${i}">Apellido Paterno</label>
          </div>
          <div class="form-floating col-4">
            <input type="text" class="form-control" name="player_mother_last_name_${i}" placeholder="Apellido Materno" required>
            <label for="player_mother_last_name_${i}">Apellido Materno</label>
          </div>
        </div>
        <div class="row g-2">
          <div class="form-floating col-4">
            <input type="date" class="form-control mb-3" name="player_birthday_${i}" required>
            <label for="player_birthday_${i}">Fecha de nacimiento</label>
          </div>
          <div class="form-floating col-4">
            <select class="form-select" name="player_gender_${i}" aria-label="Default select example required">
              <option value="" disabled selected>Seleccionar...</option>
              <option value="Mujer">Mujer</option>
              <option value="Hombre">Hombre</option>
              <option value="Otro">Administrador</option>
            </select>
            <label for="player_gender_${i}">Sexo</label>
          </div>
          <div class="form-floating col-4">
            <input type="tel" class="form-control" name="player_phone_number_${i}" placeholder="Número de Celular" required>
            <label for="player_phone_number_${i}">Número de Celular</label>
          </div>
        </div>
        
        <div class="row g-2">
          <div class="form-floating col-6 mb-3">
            <input type="email" class="form-control" name="player_email_${i}" placeholder="Correo Electrónico" required>
            <label for="player_email_${i}">Correo Electrónico</label>
          </div>
          <div class="form-floating col-3">
            <input type="number" class="form-control" name="player_semester_${i}" placeholder="Semestre" required>
            <label for="player_semester_${i}">Semestre</label>
          </div>
          <div class="form-floating col-3">
            <input type="number" class="form-control" name="player_${i}" placeholder="Grupo" required>
            <label for="player_group_${i}">Grupo</label>
          </div>
        </div>

        <label>Imagen del jugador:</label>
        <div class="">
          <input type="file" class="form-control" name="player_image_${i}" accept="image/*" required>
        </div>
        
        <input type="hidden" name="player_captain_${i}" value="1">
        <input type="hidden" name="player_captain_${i}" value=${selectedDependency}>

        `;
      } else {
        playerDiv.innerHTML = `
        <h4 class="mb-4">Jugador no. ${i + 1}</h4>
        <div class="row g-2">
          <div class="form-floating col-4">
            <input type="text" class="form-control mb-3" name="player_name_${i}" placeholder="Nombre(s)" required>
            <label for="player_name_${i}">Nombre(s)</label>
          </div>
          <div class="form-floating col-4">
            <input type="text" class="form-control" name="player_father_last_name_${i}" placeholder="Apellido Paterno" required>
            <label for="player_father_last_name_${i}">Apellido Paterno</label>
          </div>
          <div class="form-floating col-4">
            <input type="text" class="form-control" name="player_mother_last_name_${i}" placeholder="Apellido Materno" required>
            <label for="player_mother_last_name_${i}">Apellido Materno</label>
          </div>
        </div>
        <div class="row g-2">
          <div class="form-floating col-4">
            <input type="date" class="form-control mb-3" name="player_birthday_${i}" required>
            <label for="player_birthday_${i}">Fecha de nacimiento</label>
          </div>
          <div class="form-floating col-4">
            <select class="form-select" name="player_gender_${i}" aria-label="Default select example required">
              <option value="" disabled selected>Seleccionar...</option>
              <option value="Mujer">Mujer</option>
              <option value="Hombre">Hombre</option>
              <option value="Otro">Administrador</option>
            </select>
            <label for="player_gender_${i}">Sexo</label>
          </div>
          <div class="form-floating col-4">
            <input type="tel" class="form-control" name="player_phone_number_${i}" placeholder="Número de Celular" required>
            <label for="player_phone_number_${i}">Número de Celular</label>
          </div>
        </div>
        
        <div class="row g-2">
          <div class="form-floating col-6 mb-3">
            <input type="email" class="form-control" name="player_email_${i}" placeholder="Correo Electrónico" required>
            <label for="player_email_${i}">Correo Electrónico</label>
          </div>
          <div class="form-floating col-3">
            <input type="number" class="form-control" name="player_semester_${i}" placeholder="Semestre" required>
            <label for="player_semester_${i}">Semestre</label>
          </div>
          <div class="form-floating col-3">
            <input type="number" class="form-control" name="player_${i}" placeholder="Grupo" required>
            <label for="player_group_${i}">Grupo</label>
          </div>
        </div>

        <label>Imagen del jugador:</label>
        <div class="">
          <input type="file" class="form-control" name="player_image_${i}" accept="image/*" required>
        </div>
        
        <input type="hidden" name="player_captain_${i}" value="0">
        <input type="hidden" name="player_captain_${i}" value=${selectedDependency}>

      `;
      }
      form.appendChild(playerDiv);
    }

    // Generar campos para jugadores extra
    for (let i = 0; i < numExtraPlayers; i++) {
      const extraPlayerDiv = document.createElement("div");
      extraPlayerDiv.className = "player-input";
      extraPlayerDiv.innerHTML = `
        <h3>Extra Player ${i + 1}</h3>
        <input type="text" name="extra_player_name_${i}" placeholder="Name">
        <input type="date" name="extra_player_birthday_${i}" placeholder="Birthday">
        <input type="text" name="extra_player_gender_${i}" placeholder="Gender">
        <!-- Agregar más campos según tus necesidades -->
      `;
      form.appendChild(extraPlayerDiv);
    }

    // Agregar botón de envío
    const submitButton = document.createElement("button");
    submitButton.type = "submit";
    submitButton.textContent = "Submit";
    form.appendChild(submitButton);

    formContainer.innerHTML = "";
    formContainer.appendChild(form);
  }
});

