<div class="container">
  <header class="d-flex flex-wrap py-3 mb-5 border-bottom border-4 border-dark">
    <span class="fw-bold text-dark" style="font-size: 45px">DEPENDENCIAS</span>
  </header>
</div>
</div>
<div class="container text-end mb-5">
  <div class="row">
    <div class="col-lg-12">
      <button id="addDependency" type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal"
        data-bs-target="#modalDependency">
        Agregar Dependencia
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
        <th>Dependencia</th>
        <th>Categoría</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<!-- Modal CRUD -->
<div class="modal fade" id="modalDependency" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="dependencyForm" enctype="multipart/form-data">
        <div class="modal-content border-0">
          <div class="modal-body">
            <input type="hidden" name="id" id="id">
            <div class="form-floating">
              <input type="text" class="form-control mb-4" name="name" id="name" placeholder="Nombre de la dependencia"
                required>
              <label for="name">Nombre de la dependencia</label>
            </div>
            <div class="form-floating">
              <input type="text" class="form-control mb-4" name="category" id="category" placeholder="Categoría"
                required>
              <label for="name">Categoría</label>
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