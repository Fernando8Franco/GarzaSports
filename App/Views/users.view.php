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
  <table id="datatable_events" class="table table-bordered table-striped" style="width:100%">
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