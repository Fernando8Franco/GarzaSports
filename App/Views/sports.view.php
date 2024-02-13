<div class="container">
  <header class="d-flex flex-wrap py-3 mb-5 border-bottom border-4 border-dark">
    <span class="fw-bold text-dark" style="font-size: 45px">DEPORTES</span>
  </header>
</div>
<div class="container text-end mb-5">
  <div class="row">
    <div class="col-lg-12">
      <button id="addSport" type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal"
        data-bs-target="#modalSport">
        Agregar Deporte
        <i class="fa-solid fa-circle-plus"></i>
      </button>
    </div>
  </div>
</div>
<div class="container table-responsive pt-2">
  <table id="datatable" class="table table-bordered table-striped" style="width:100%">
    <thead>
      <tr>
        <th>id</th>
        <th>Deporte</th>
        <th>Tipo de deporte</th>
        <th>Rama</th>
        <th>No. de jugadores</th>
        <th>No. de jugadores extra</th>
        <th>Requiere capitán</th>
        <th>Activo</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<!-- Modal CRUD -->
<div class="modal fade" id="modalSport" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="sportForm" enctype="multipart/form-data">
        <div class="modal-content border-0">
          <div class="modal-body">
            <input type="hidden" name="id" id="id">
            <div class="form-floating">
              <input type="text" class="form-control mb-4" name="name" id="name" placeholder="Nombre del Evento"
                required onblur="this.value=this.value.trim()">
              <label for="name">Nombre del Deporte</label>
            </div>
            <div class="row g-2">
              <div class="form-floating col-6">
                <select class="form-select mb-4" id="type" name="type" aria-label="Default select example" required>
                  <option value="" disabled selected>Seleccionar...</option>
                  <option value="Equipo">Equipo</option>
                  <option value="Individual">Individual</option>
                </select>
                <label for="type">Tipo</label>
              </div>
              <div class="form-floating col-6">
                <select class="form-select mb-4" id="gender" name="gender" aria-label="Default select example" required>
                  <option value="" disabled selected>Seleccionar...</option>
                  <option value="Varonil">Varonil</option>
                  <option value="Femenil">Femenil</option>
                </select>
                <label for="gender">Rama</label>
              </div>
            </div>
            <div class="row g-2">
              <div class="form-floating col-6">
                <input type="number" class="form-control mb-4" name="num_players" id="num_players"
                  placeholder="Nombre del Evento" required>
                <label for="num_players">No. jugadores</label>
              </div>
              <div class="form-floating col-6">
                <input type="number" class="form-control mb-4" name="num_extraplayers" id="num_extraplayers"
                  placeholder="Nombre del Evento" required>
                <label for="num_extraplayers">Jugadores extra</label>
              </div>
            </div>
            <div class="row g-2">
              <div class="form-floating col-6">
                <select class="form-select mb-4" id="has_captain" name="has_captain" aria-label="Default select example">
                  <option value="1">Si</option>
                  <option value="0">No</option>
                </select>
                <label for="has_captain">¿Requiere Capitan?</label>
              </div>
              <div class="form-floating col-6">
                <select class="form-select mb-4" id="is_active" name="is_active" aria-label="Default select example">
                  <option value="1">Si</option>
                  <option value="0">No</option>
                </select>
                <label for="is_active">¿Está activo?</label>
              </div>
            </div>
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