<?php
        $url = explode('/', URL);
        $page = $url[1];

        if (strtolower($page) === "index" || $page === "") {
            $pageName = "Registro";
        }else if (strtolower($page) === "admin") {
            $pageName = "Admin LogIn";
        }  
?>

<!DOCTYPE html>
<html lang="es" id="htmlLogin">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="x-icon" href="<?= URL_PATH ?>/assets/images/garzaIcon.png">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/styles.index.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/fontawesome-6/css/fontawesome.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/fontawesome-6/css/brands.css">
    <link rel="stylesheet" href="<?= URL_PATH ?>/assets/fontawesome-6/css/solid.css">

    
    <title><?= $pageName ?></title>
</head>
    <body>
        <?= $content ?>

        <!-- Scripts -->
        <script src="<?= URL_PATH ?>/assets/bootstrap-5/js/bootstrap.min.js"></script>
        <script src="<?= URL_PATH ?>/assets/js/scripts.js"></script>
    </body>
</html>