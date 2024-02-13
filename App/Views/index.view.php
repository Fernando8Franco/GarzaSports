<?php
$url = URL_HOST . URL_PATH . "/events/eventsDatesWithoutFormat";

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);

if ($response === false) {
    echo 'Error al consumir la API: ' . curl_error($curl);
} else {
    $data = json_decode($response, true);
}

curl_close($curl);
?>
<div class="container text-center">
  <div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-2 border-bottom border-4 border-dark">
      <img class="img-index" id="img-banner" src="<?= URL_PATH . $data['banner'] ?>" alt="banner">
      <span class="fw-bold text-white event_name" id="eventName"><?= $data['name'] ?></span>
    </header>
  </div>
</div>
<section class="indexSection d-flex justify-content-center align-items-center" id="index">
  <div class="container my-1 text-center">
    <div class="row">
      <div class="col-lg-7 m-auto">
        <div class="card border-0 shadow-lg">
          <div class="my-3">
            <div class="my-3">
              <i class="fa-solid fa-user indexIcon"></i>
            </div>
            <p class="h3">Registro</p>
            <div class="card-body">
              <button type="submit" class="btn btn-danger btn-lg" id="register">
                Acceder
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container my-1 text-center">
    <div class="row">
      <div class="col-lg-7 m-auto">
        <div class="card border-0 shadow-lg">
          <div class="my-3">
            <div class="my-3">
              <i class="fa-solid fa-users-viewfinder indexIcon"></i>
            </div>
            <p class="h3">Ver Registro</p>
            <div class="card-body">
              <button type="submit" class="btn btn-danger btn-lg" id="search">
                Acceder
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="text-end">
  <button class="btn text-white" id="adminBtn">
    <i class="fa-solid fa-address-card" style="font-size: 20px;"></i>
  </button>
</div>