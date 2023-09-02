<div class="container my-5 text-center">
  <div class="container bg">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom border-4 border-dark">
      <span class="fw-bold text-white event_name" id="eventName"> </span>
    </header>
  </div>
</div>

<div class="container">
  <div class="container bg-white rounded py-5">
    <section>
      <div class="form-floating">
        <select class="form-select mb-4" id="category" name="category" aria-label="Default select example"
          hx-trigger="load" hx-post="<?= URL_PATH ?>/dependencies/getcategories" hx-target="#category" required>
          <option value="" disabled selected>Seleccionar...</option>
        </select>
        <label for="category">Categoria</label>
      </div>
      <div class="form-floating">
        <select class="form-select mb-4" id="dependency" name="dependency" aria-label="Default select example" required>
          <option value="" disabled selected>Seleccionar...</option>
        </select>
        <label for="dependency">Depenencia</label>
      </div>
      <div class="form-floating">
        <select class="form-select mb-4" id="branch" name="branch" aria-label="Default select example" required>
          <option value="" disabled selected>Seleccionar...</option>
        </select>
        <label for="branch">Rama</label>
      </div>
      <div class="form-floating">
        <select class="form-select mb-4" id="sport" name="sport" aria-label="Default select example" required>
          <option value="" disabled selected>Seleccionar...</option>
        </select>
        <label for="sport">Deporte</label>
      </div>
    </section>

    <section id="form-container">

    </section>
  </div>
</div>