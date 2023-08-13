<header class="header" id="header">
  <div class="header_toggle">
    <i class="fa-solid fa-bars-staggered" id="header-toggle"></i>
  </div>
  <div class="ms-auto">
    <i class="fa-solid fa-address-card"></i> Luis Franco - Administrador
  </div>
</header>
<div class="l-navbar" id="nav-bar">
  <nav class="nav">
    <div>
      <a href="" class="nav_logo">
        <i class="fa-solid fa-ranking-star nav_logo-icon"></i>
        <span class="nav_logo-name">Garza Sports</span>
      </a>
      <div class="nav_list">
        <a href="" class="nav_link active" hx-get="<?= URL_PATH ?>/admin/start" hx-target="#targetPHP">
        <i class="fa-solid fa-gauge-high nav_icon"></i>
          <span class="nav_name">Inicio</span>
        </a>
        <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/events/index" hx-target="#targetPHP">
        <i class="fa-solid fa-calendar-days nav_icon"></i>
          <span class="nav_name">Eventos</span>
        </a>
        <a href="#" class="nav_link" hx-get="<?= URL_PATH ?>/users/index" hx-target="#targetPHP">
        <i class="fa-solid fa-user-gear nav_icon"></i>
          <span class="nav_name">Usuarios</span>
        </a>
        <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/dependencies/index" hx-target="#targetPHP">
        <i class="fa-solid fa-landmark nav_icon"></i>
          <span class="nav_name">Dependencias</span>
        </a>
        <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/sports/dependencies/index" hx-target="#targetPHP">
          <i class="fa-solid fa-volleyball nav_icon"></i>
          <span class="nav_name">Deportes</span>
        </a>
        <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/records/index" hx-target="#targetPHP">
          <i class="fa-solid fa-file-pen nav_icon"></i>
          <span class="nav_name">Inscripciones</span>
        </a>
        <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/players/index" hx-target="#targetPHP">
          <i class="fa-solid fa-address-book nav_icon"></i>
          <span class="nav_name">Participantes</span>
        </a>
        <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/teams/index" hx-target="#targetPHP">
          <i class="fa-solid fa-people-group fa-lg nav_icon"></i>
          <span class="nav_name">Equipos</span>
        </a>
      </div>
    </div>
    <a href="" class="nav_link">
      <i class="fa-solid fa-arrow-right-from-bracket nav_icon"></i>
      <span class="nav_name">Cerrar Sesión</span>
    </a>
  </nav>
</div>
<!--Container Main start-->
<div class="main" hx-get="<?= URL_PATH ?>/admin/start" hx-trigger="load" hx-target="#targetPHP">
  <main class="content px-3 py-2">
      <div class="container-fluid">
          <div class="mb-3" id="targetPHP">
              
          </div>
      </div>
  </main>
</div>
<!--Container Main end-->
