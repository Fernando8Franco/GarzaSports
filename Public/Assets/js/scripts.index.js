document.addEventListener("DOMContentLoaded", function () {
  const adminBtn = document.getElementById("adminBtn")
  adminBtn.addEventListener("click", function() {
    window.open(URL_PATH + "/admin", "_blank");
  });

  const fetchPromise = fetchEventDates();
  fetchPromise.then((data) => {
    const eventName = data.name;
    document.getElementById("eventName").textContent = "Registro " + eventName;
  });

  const buttonSearch = document.getElementById("search");
  const buttonRegister = document.getElementById("register");

  buttonSearch.addEventListener("click", function () {
    Swal.fire({
      allowOutsideClick: false,
      width: 150,
      didOpen: () => {
        Swal.showLoading();
      },
    });
    window.location.href = URL_PATH + "/index/search";
  });

  buttonRegister.addEventListener("click", function () {
    Swal.fire({
      allowOutsideClick: false,
      width: 150,
      didOpen: () => {
        Swal.showLoading();
      },
    });
    const fetchPromise = fetchEventDates();
    fetchPromise.then((data) => {
      if(dateBetween(data.ins_start_date, data.ins_end_date, getActualDate())) {
        window.location.href = URL_PATH + "/index/registroUAEH";
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Periodo acabado',
          text: 'El periodo de inscripción se ha acabado'
        })
      }
    });
  });
});

async function fetchEventDates() {
  const url = `${URL_PATH}/events/eventsDatesWithoutFormat`;

  return fetch(url, {
    method: "GET",
    dataType: "json",
  })
    .then((response) => response.json())
    .catch((error) => {
      console.error("Error:", error);
    });
}

function dateBetween(startDate, endDate, checkDate) {
  const startDateObj = new Date(startDate);
  const endDateObj = new Date(endDate);
  const checkDateObj = new Date(checkDate);

  return checkDateObj >= startDateObj && checkDateObj <= endDateObj;
}

function getActualDate() {
  const months = [
    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
  ];

  const actualDateUTC = new Date();
  const CDMXTimeZone = 'America/Mexico_City';
  const actualDateCDMX = new Date(actualDateUTC.toLocaleString('en-US', { timeZone: CDMXTimeZone }));

  const day = actualDateCDMX.getDate();
  const month = months[actualDateCDMX.getMonth()];
  const year = actualDateCDMX.getFullYear();

  return `${day} ${month} ${year}`;
}