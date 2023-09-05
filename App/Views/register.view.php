<div class="container">
  <header class="d-flex flex-wrap py-3 mb-5 border-bottom border-4 border-dark">
    <span class="fw-bold text-dark" style="font-size: 45px">REGISTROS</span>
  </header>
</div>
<button id="addRegister" type="button" class="d-none"></button>
<button id="editRegister" type="button" class="d-none"></button>
<button id="deleteRegister" type="button" class="d-none"></button>
<div class="container table-responsive pt-2">
  <table id="datatable" class="table table-bordered table-striped" style="width:100%">
    <thead>
      <tr>
        <th>id</th>
        <th>Equipo y Fecha registro</th>
        <th>Dependencia</th>
        <th>Deporte</th>
        <th>Datos del Participante</th>
        <th>Foto</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<!-- Modal CRUD -->
<div class="modal fade" id="modalRegister" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="registerForm" enctype="multipart/form-data">
        <div class="modal-footer justify-content-center">
          <button type="submit" name="action" id="action" class="btn btn-danger btn-lg">

          </button>
        </div>
      </form>
    </div>
  </div>
</div>