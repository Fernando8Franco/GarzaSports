<?php
    if(isset($_POST['external'])){
        $url = URL_PATH;
        header("Location: {$url}/admin/");
    }else if(isset($_POST['internal'])){
        $url = URL_PATH;
        header("Location: {$url}/admin/dashboard");
    }
?>

<div class="container my-5 text-center">
<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom border-4 border-dark">
        <span class="fw-bold text-white event_name">REGISTRO NOMBRE EVENTO</span>
    </header>
  </div>
</div>
<section class="indexSection d-flex justify-content-center align-items-center" id="index">
    <div class="container my-5 text-center">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="card border-0 shadow-lg">
                    <div class="my-3">
                        <div class="my-3">
                            <i class="fa-solid fa-user-group" style="font-size: 128px"></i>
                        </div>
                        <p class="h3">Registro personas externas</p>
                        <div class="card-body">
                            <form method="POST" action="">
                                <input type="submit" class="btn btn-danger btn-lg" value="Acceder" name="external">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container my-5 text-center">
        <div class="row">
            <div class="col-lg-8 m-auto">
                <div class="card border-0 shadow-lg">
                    <div class="my-3">
                        <div class="my-3">
                        <i class="fa-solid fa-user" style="font-size: 128px"></i>
                        </div>
                        <p class="h3">Registro personas UAEH</p>
                        <div class="card-body">
                            <form method="POST" action="">
                                <input type="submit" class="btn btn-danger btn-lg" value="Acceder" name="internal">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>