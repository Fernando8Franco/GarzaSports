<div class="container">
  <header class="d-flex flex-wrap py-3 mb-5 border-bottom border-4 border-dark">
    <span class="fw-bold text-dark" style="font-size: 45px">USUARIOS</span>
  </header>
</div>
</div>
<div class="container text-end mb-5">
  <div class="row">
    <div class="col-lg-12">
      <button id="addEmployee" type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal"
        data-bs-target="#modalEmployee">
        Agregar Usuario
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
        <th>id_dependencia</th>
        <th>Número de Empleado</th>
        <th>Dependencia</th>
        <th>Nombre(s)</th>
        <th>Apellidos</th>
        <!-- <th>Apellido Materno</th> -->
        <th>Rol</th>
        <th>Estatus</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<!-- Modal CRUD -->
<div class="modal fade" id="modalEmployee" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="employeeForm" enctype="multipart/form-data">
        <div class="modal-content border-0">
          <div class="modal-body">
            <input type="hidden" name="id" id="id">
            <div class="row g-2" id="div_acc_pass">
              <div class="form-floating col-6" id="div_on_employee">
                <input type="text" class="form-control mb-4" name="no_employee" id="no_employee"
                  placeholder="Número de empleado" required>
                <label for="no_employee">Número de empleado</label>
              </div>
              <div class="form-floating col-6">
                <input type="password" class="form-control mb-4" name="password" id="password" placeholder="Contraseña"
                  required>
                <label for="password"=>Contraseña</label>
              </div>
            </div>
            <div class="form-floating">
              <select class="form-select mb-4" id="dependency_name" name="id_dependency"
                aria-label="Default select example" hx-trigger="load"
                hx-put="<?= URL_PATH ?>/dependencies/getdependencies" hx-target="#dependency_name" required>

              </select>
              <label for="dependency_name">Dependencia</label>
            </div>
            <div class="form-floating">
              <input type="text" class="form-control mb-4" name="name_s" id="name_s" placeholder="Nombre(s)" required>
              <label for="name_s">Nombre(s)</label>
            </div>
            <div class="row g-2">
              <div class="form-floating col-6">
                <input type="text" class="form-control mb-4" name="father_last_name" id="father_last_name"
                  placeholder="Apellido Paterno" required>
                <label for="father_last_name">Apellido Paterno</label>
              </div>
              <div class="form-floating col-6">
                <input type="text" class="form-control mb-4" name="mother_last_name" id="mother_last_name"
                  placeholder="Apellido Materno" required>
                <label for="mother_last_name">Apellido Materno</label>
              </div>
            </div>
            <div class="row g-2">
              <div class="form-floating col-6">
                <select class="form-select mb-4" id="role_emp" name="role_emp" aria-label="Default select example">
                  <option value="Empleado">Empleado</option>
                  <option value="Administrador">Administrador</option>
                </select>
                <label for="role_emp">Rol del empleado</label>
              </div>
              <div class="form-floating col-6">
                <select class="form-select mb-4" id="is_active" name="is_active" aria-label="Default select example">
                  <option value="0">INACTIVO</option>
                  <option value="1">ACTIVO</option>
                </select>
                <label for="is_active">Status</label>
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