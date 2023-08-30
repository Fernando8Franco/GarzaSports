<div class="container">
  <header class="d-flex flex-wrap py-3 mb-5 border-bottom border-4 border-dark">
    <span class="fw-bold text-dark" style="font-size: 45px">EQUIPOS</span>
  </header>
</div>
<button id="addTeam" type="button" class="btn btn-danger btn-lg d-none" data-bs-toggle="modal" data-bs-target="#">
  <i class="fa-solid fa-circle-plus"></i>
</button>
<div class="container table-responsive pt-2">
  <table id="datatable" class="table table-bordered table-striped" style="width:100%">
    <thead>
      <tr>
        <th>id</th>
        <th>Nombre del Equipo</th>
        <th>Fecha de registro</th>
        <th>Dependencia</th>
        <th>Deporte</th>
        <th>Evento</th>
        <th>Acciones</th>
      </tr>
    </thead>
  </table>
</div>

<!-- Modal CRUD -->
<div class="modal fade" id="modalTeam" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="teamForm" enctype="multipart/form-data">
        <div class="modal-content border-0">
          <div class="modal-body">
            <input type="hidden" name="id" id="id">
            <div class="form-floating">
              <input type="text" class="form-control mb-4" name="name" id="name" placeholder="Nombre del Evento"
                required>
              <label for="name">Nombre del Deporte</label>
            </div>
            <div class="form-floating">
              <input type="disabledTextInput" class="form-control mb-4" name="record_date" id="record_date"
                placeholder="Nombre del Evento" disabled>
              <label for="record_date">Fecha de registro</label>
            </div>
            <div class="form-floating">
              <input type="disabledTextInput" class="form-control mb-4" name="dependency_name" id="dependency_name"
                placeholder="Nombre del Evento" disabled>
              <label for="dependency_name">Dependencia</label>
            </div>
            <div class="form-floating">
              <input type="disabledTextInput" class="form-control mb-4" name="sport_name" id="sport_name"
                placeholder="Nombre del Evento" disabled>
              <label for="sport_name">Deporte</label>
            </div>
            <div class="form-floating">
              <input type="disabledTextInput" class="form-control mb-4" name="event_name" id="event_name"
                placeholder="Nombre del Evento" disabled>
              <label for="event_name">Evento</label>
            </div>
            <div class="modal-footer justify-content-center">
              <button type="submit" name="action" id="action" class="btn btn-danger btn-lg">

              </button>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>