<?php if (isset($_SESSION['role_emp'])): ?>
  <div class="container">
    <header class="d-flex flex-wrap py-3 mb-5 border-bottom border-4 border-dark">
      <span class="fw-bold text-dark" style="font-size: 45px">EVENTOS</span>
    </header>
  </div>
  </div>
  <div class="container text-end mb-5">
    <div class="row">
      <div class="col-lg-12">
        <button id="addEvent" type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal"
          data-bs-target="#modalEvent">
          Agregar Evento
          <i class="fa-regular fa-calendar-plus"></i>
        </button>
      </div>
    </div>
  </div>
  <div class="container table-responsive pt-2">
    <table id="datatable" class="table table-bordered table-striped" style="width:100%">
      <thead>
        <tr>
          <th>id</th>
          <th>Nombre Evento</th>
          <th>Inicio Evento</th>
          <th>Finalización Evento</th>
          <th>Inicio inscripción al evento</th>
          <th>Fin inscripción al evento</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <!-- Modal CRUD -->
  <div class="modal fade" id="modalEvent" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="eventForm" enctype="multipart/form-data">
          <div class="modal-content border-0">
            <div class="modal-body">
              <input type="hidden" name="id" id="id">
              <div class="form-floating">
                <input type="text" class="form-control mb-4" name="name" id="name" placeholder="Nombre del Evento"
                  required>
                <label for="name">Nombre del evento</label>
              </div>
              <div class="col-12">
                <div class="row">
                  <div class="col-6">
                    <label>Inicio del evento</label>
                    <input type="date" name="start_event" id="start_event" class="form-control mb-4" required>
                  </div>
                  <div class="col-6">
                    <label>Finalización del evento</label>
                    <input type="date" name="end_event" id="end_event" class="form-control" required>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="row">
                  <div class="col-6">
                    <label>Inicio inscripción</label>
                    <input type="date" name="ins_start_event" id="ins_start_event" class="form-control mb-4" required>
                  </div>
                  <div class="col-6">
                    <label>Finalización inscripción</label>
                    <input type="date" name="ins_end_event" id="ins_end_event" class="form-control" required>
                  </div>
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
<?php endif; ?>