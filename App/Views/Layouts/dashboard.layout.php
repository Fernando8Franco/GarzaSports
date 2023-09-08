<?php
$currentDateSQL = date("Y-m-d");
if (!isset($_SESSION['name_s'])) {
  header("Location: " . URL_PATH . "/admin");
  exit();
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
  <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/styles.default.css">
  <link rel="stylesheet" href="<?= URL_PATH ?>/assets/css/styles.dashboard.css">
  <link rel="stylesheet" href="<?= URL_PATH ?>/assets/fontawesome-6/css/fontawesome.css">
  <link rel="stylesheet" href="<?= URL_PATH ?>/assets/fontawesome-6/css/brands.css">
  <link rel="stylesheet" href="<?= URL_PATH ?>/assets/fontawesome-6/css/solid.css">
  <link rel="stylesheet"
    href="<?= URL_PATH ?>/assets/datatables/css/cdn.datatables.net_1.13.6_css_dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="<?= URL_PATH ?>/assets/datatables/css/cdn.datatables.net_buttons_2.4.1_css_buttons.dataTables.min.css">
  <link rel="stylesheet" href="<?= URL_PATH ?>/assets/sweetalert2/css/sweetalert2.min.css">

  <title>Panel de Control</title>
</head>

<body id="body-pd">
  <?= $content ?>
  <!-- Scripts -->
  <script> const URL_PATH = "<?= URL_PATH ?>"; </script>
  <script src="<?= URL_PATH ?>/assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
  <script src="<?= URL_PATH ?>/assets/js/htmx-1.9.min.js"></script>
  <script src="<?= URL_PATH ?>/assets/datatables/js/code.jquery.com_jquery-3.7.0.js"></script>
  <script src="<?= URL_PATH ?>/assets/datatables/js/cdn.datatables.net_1.13.6_js_jquery.dataTables.min.js"></script>
  <script src="<?= URL_PATH ?>/assets/datatables/js/cdn.datatables.net_1.13.6_js_dataTables.bootstrap5.min.js"></script>
  <script src="<?= URL_PATH ?>/assets/datatables/js/cdn.datatables.net_buttons_2.4.1_js_dataTables.buttons.min.js"></script>
  <script src="<?= URL_PATH ?>/assets/datatables/js/cdn.datatables.net_buttons_2.4.1_js_buttons.print.min.js"></script>
  <script src="<?= URL_PATH ?>/assets/js/scripts.dashboard.js"></script>
  <script src="<?= URL_PATH ?>/assets/sweetalert2/js/sweetalert2.all.min.js"></script>
</body>

</html>