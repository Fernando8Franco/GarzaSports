<section class="loginSection d-flex justify-content-center align-items-center">
    <div class="container my-5 text-center">
        <div class="row">
            <div class="col-sm-8 col-md-5 col-lg-4 m-auto">
                <div class="card border-0 shadow-lg">
                    <div class="my-3">
                        <img src="<?= URL_PATH ?>/assets/images/garza.png" alt="Imagen Garza" width="125">
                    </div>
                    <p class="h3"><i class="fa-solid fa-address-card"></i> Acceso Empleados</p>
                    <div class="card-body">
                        <form id="loginForm">
                            <input type="text" name="user" class="form-control my-3 py-2 text-center" style="font-size: 24px" placeholder="No. cuenta">
                            <input type="password" name="password" class="form-control my-3 py-2 text-center"  style="font-size: 24px" placeholder="NIP">
                            <div class="mt-3">
                            <button type="submit" class="btn btn-danger btn-lg">Acceder</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>