<div class="container my-5 text-center">
  <div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom border-4 border-dark">
      <span class="fw-bold text-white event_name">BUSCAR REGISTRO</span>
    </header>
  </div>
</div>

<div class="container-fluid">
  <div class="container bg-white rounded py-5">
    <section>
      <form id="searchForm">
        <div class="form-floating">
          <input type="text" class="form-control mb-3" name="acc_number" placeholder="Número de cuenta" maxlength="10"
            required>
          <label for="acc_number[]">Número de cuenta</label>
        </div>
        <div class="form-floating mb-3">
          <input type="email" class="form-control" name="email" placeholder="Correo Electrónico" maxlength="50"
            required>
          <label for="email[]">Correo Electrónico</label>
        </div>
        <div class="modal-footer justify-content-center pt-3">
          <button type="submit" name="action" id="action" class="btn btn-danger btn-lg">
            Buscar <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </div>
      </form>
    </section>

    <section id="container" style="display: none;">
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
    </section>
  </div>
</div>