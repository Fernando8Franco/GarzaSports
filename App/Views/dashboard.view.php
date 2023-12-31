<header class="header" id="header">
  <div class="header_toggle">
    <i class="fa-solid fa-bars-staggered" id="header-toggle"></i>
  </div>
  <div class="ms-auto">
    <i class="fa-solid fa-address-card"></i>
    <?= $_SESSION['name_s'] ?> -
    <?= $_SESSION['role_emp'] ?>
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
          <a href="" class="nav_link active" hx-get="<?= URL_PATH ?>/admin/start" hx-target="#targetPHP"
            hx-trigger="click" id="dashboard_link" title="Dashboard">
            <i class="fa-solid fa-gauge-high nav_icon"></i>
            <span class="nav_name">Inicio</span>
          </a>

          <?php if ($_SESSION['role_emp'] === 'Administrador'): ?>
            <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/events/index" hx-target="#targetPHP" hx-trigger="click"
              id="events_link" title="Eventos">
              <i class="fa-solid fa-calendar-days nav_icon"></i>
              <span class="nav_name">Eventos</span>
            </a>
            <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/employees/index" hx-target="#targetPHP" hx-trigger="click"
              id="employees_link" title="Empleados">
              <i class="fa-solid fa-user-gear nav_icon"></i>
              <span class="nav_name">Usuarios</span>
            </a>
            <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/dependencies/index" hx-target="#targetPHP"
              hx-trigger="click" id="dependencies_link" title="Dependencias">
              <i class="fa-solid fa-landmark nav_icon"></i>
              <span class="nav_name">Dependencias</span>
            </a>
            <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/sports/index" hx-target="#targetPHP" hx-trigger="click"
              id="sports_link" title="Deportes">
              <i class="fa-solid fa-volleyball nav_icon"></i>
              <span class="nav_name">Deportes</span>
            </a>
            <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/register/index" hx-target="#targetPHP" hx-trigger="click"
              id="register_link" title="Inscripciones">
              <i class="fa-solid fa-file-pen nav_icon"></i>
              <span class="nav_name">Inscripciones</span>
            </a>
            <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/teams/index" hx-target="#targetPHP" hx-trigger="click"
              id="teams_link" title="Equipos">
              <i class="fa-solid fa-people-group fa-lg nav_icon"></i>
              <span class="nav_name">Equipos</span>
            </a>
          <?php endif; ?>
          
          <?php if ($_SESSION['role_emp'] === 'Empleado'): ?>
            <a href="" class="nav_link" hx-get="<?= URL_PATH ?>/register/registerByDependency" hx-target="#targetPHP" hx-trigger="click" id="registerByDependency_link" title="Inscripciones">
              <i class="fa-solid fa-file-pen nav_icon"></i>
              <span class="nav_name">Inscripciones</span>
            </a>
          <?php endif; ?>
        </div>
      </div>
      <a href="<?= URL_PATH ?>/admin/logout" class="nav_link">
        <i class="fa-solid fa-arrow-right-from-bracket nav_icon"></i>
        <span class="nav_name">Cerrar Sesión</span>
      </a>
    </nav>
  </div>
<!--Container Main start-->
<div class="main">
  <main class="content px-3 py-2">
    <div class="container-fluid">
      <div class="mb-3" id="targetPHP" hx-get="<?= URL_PATH ?>/admin/start" hx-trigger="load once"
        hx-target="#targetPHP">
      </div>
    </div>
  </main>
</div>
<!--Container Main end-->