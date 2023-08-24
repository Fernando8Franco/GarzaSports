<section class="loginSection d-flex justify-content-center align-items-center">
  <div class="container my-5 text-center">
    <div class="row">
      <div class="col-sm-8 col-md-5 col-lg-4 m-auto">
        <div class="card border-0 shadow-lg">
          <div class="my-3">
            <img src="<?= URL_PATH ?>/assets/images/garza.png" alt="Imagen Garza" width="125">
          </div>
          <p class="h3"><i class="fa-solid fa-address-card"></i> Acceso Empleados</p>
          <div class="card-body">
            <form id="loginForm">
              <input type="text" name="no_employee" class="form-control my-3 py-2 text-center" style="font-size: 24px"
                placeholder="No. cuenta" required>
              <input type="password" name="password" class="form-control my-3 py-2 text-center" style="font-size: 24px"
                placeholder="NIP" required>
              <div class="mt-3">
                <button type="submit" class="btn btn-danger btn-lg">Acceder</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade responseModalError" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-center align-items-center border-0 bg-danger">
        <div class="row">
          <div class="col-auto icon-box py-4 fa-3x">
            <i class="fa-regular fa-circle-xmark fa-beat" style="font-size: 100px"></i>
          </div>
        </div>
      </div>
      <div class="modal-body">
        <p class="response-message text-center fs-3 py-3 mb-0">Contenido del mensaje de respuesta.</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-danger btn-lg" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    const responseModal = new bootstrap.Modal(document.getElementById("responseModal"));
    const responseMessage = document.querySelector(".response-message");

    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(form);
      const xhr = new XMLHttpRequest();

      xhr.open("POST", `${URL_PATH}/admin/login`, true);
      xhr.onload = function () {
        if (xhr.status === 200) {
          responseMessage.textContent = xhr.responseText;
          if (!xhr.responseText.includes("Acceso consedido")) {
            responseModal.show();
          }
          if (xhr.responseText.includes("Acceso consedido")) {
            window.location.href = `${URL_PATH}/admin/dashboard`;
          }
        } else {
          responseMessage.textContent = "Error en la solicitud.";
          responseModal.show(); // Abre el modal
        }
      };
      xhr.send(formData);
    });
  });
</script>