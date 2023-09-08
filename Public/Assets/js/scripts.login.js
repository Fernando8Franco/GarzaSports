document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("loginForm");
  const responseModal = new bootstrap.Modal(
    document.getElementById("responseModal")
  );
  const responseMessage = document.querySelector(".response-message");

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    const fetchPromiseLogin = fetchLogin(formData);
    fetchPromiseLogin.then((data) => {
      if(data == 'Acceso consedido'){
        window.location.href = `${URL_PATH}/admin/dashboard`;
        Swal.fire({
          allowOutsideClick: false,
          width: 150,
          didOpen: () => {
            Swal.showLoading();
          }
        })
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error en la solicitud',
          text: 'Usuario o contraseña incorrecto'
        })
      }
    });
  });
});

async function fetchLogin(formData) {
  const url = `${URL_PATH}/admin/login`;

  return fetch(url, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .catch((error) => {
      console.error("Error:", error);
    });
}
