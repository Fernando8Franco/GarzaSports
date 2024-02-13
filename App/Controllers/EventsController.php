<?php

require_once(__DIR__ . '/../models/event.php');

class EventsController extends Controller
{
  private $eventModel;

  public function __construct(PDO $con)
  {
    parent::__construct($con);
    $this->eventModel = new Event($con);
  }

  public function index()
  {
    !empty($_SESSION['role_emp']) && $_SESSION['role_emp'] == 'Administrador' ? $this->renderView('events') : $this->render('404', 'empty');
  }

  public function eventsCRUD()
  {
    if ($_SESSION['role_emp'] == 'Administrador') {
      if(isset($_FILES['banner'])) {
        $file = $_FILES['banner'];
        
        // Verificamos si no ha ocurrido un error durante la carga del archivo
        if($file['error'] === 0) {
          $fileName = $file['name'];
          $fileTmpName = $file['tmp_name'];
          $fileSize = $file['size'];
          $fileError = $file['error'];
          $fileType = $file['type'];

          $newFileName = uniqid('', true) . '_' . $fileName;
          
          // Movemos el archivo temporal a una ubicación permanente en el servidor
          $uploadPath = str_replace("App", "Public", SITE_ROOT) . '\\assets\\images\\banners\\' . $newFileName;
          $uploadPathWeb = '/assets/images/banners/' . $newFileName;
          move_uploaded_file($fileTmpName, $uploadPath);
        }
      }

      $data = [
        'id' => $_POST['id'] ?? '',
        'name' => $_POST['name'] ?? '',
        'start_date' => $_POST['start_event'] ?? '',
        'end_date' => $_POST['end_event'] ?? '',
        'ins_start_date' => $_POST['ins_start_event'] ?? '',
        'ins_end_date' => $_POST['ins_end_event'] ?? '',
        'banner' => $uploadPathWeb ?? '/assets/images/banners/default-banner.png'
      ];

      $option = $_POST['option'] ?? '';

      if (!empty($_SESSION['role_emp']) && $_SESSION['role_emp'] == 'Administrador') {
        switch ($option) {
          case 1:
            $this->eventModel->insert($data);
            $events = $this->eventModel->getAll();
            break;
          case 2:
            $this->eventModel->updateById($data['id'], $data);
            $events = $this->eventModel->getAll();
            break;
          case 3:
            $this->eventModel->deleteByIdEvent($data['id']);
            $events = $this->eventModel->getAll();
            break;
          case 4:
            $events = $this->eventModel->getAll();
            break;
        }
      }

      foreach ($events as &$event) {
        $event['start_date'] = $this->formatDate($event['start_date']);
        $event['end_date'] = $this->formatDate($event['end_date']);
        $event['ins_start_date'] = $this->formatDate($event['ins_start_date']);
        $event['ins_end_date'] = $this->formatDate($event['ins_end_date']);
      }

      echo json_encode($events, JSON_UNESCAPED_UNICODE);
    }
  }

  public function eventsDates()
  {
    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");

    $event = $this->eventModel->getByBetween($actualDate, 'start_date', 'end_date');

    if ($event) {
      $event['start_date'] = $this->formatDate($event['start_date']);
      $event['end_date'] = $this->formatDate($event['end_date']);
      $event['ins_start_date'] = $this->formatDate($event['ins_start_date']);
      $event['ins_end_date'] = $this->formatDate($event['ins_end_date']);

      echo json_encode($event, JSON_UNESCAPED_UNICODE);
    } else {
      $event = [
        "id" => 0,
        "name" => "GarzaSports",
        "start_date" => "Sin definir",
        "end_date" => "Sin definir",
        "inst_start_date" => "Sin definir",
        "inst_end_date" => "Sin definir"
      ];
      echo json_encode($event, JSON_UNESCAPED_UNICODE);
    }
  }

  public function eventsDatesWithoutFormat()
  {
    date_default_timezone_set("America/Mexico_City");
    $actualDate = date("Y-m-d");

    $event = $this->eventModel->getByBetween($actualDate, 'start_date', 'end_date');

    if ($event) {
      echo json_encode($event, JSON_UNESCAPED_UNICODE);
    } else {
      $event = [
        "id" => 0,
        "name" => "GarzaSports",
        "start_date" => "Sin definir",
        "end_date" => "Sin definir",
        "inst_start_date" => "Sin definir",
        "inst_end_date" => "Sin definir",
        "banner" => "/assets/images/banners/default-banner.png"
      ];
      echo json_encode($event, JSON_UNESCAPED_UNICODE);
    }
  }

  public function eventsform() {
    $columns = [
      'id' => 'id',
      'name' => 'name'
    ];
    $events = $this->eventModel->getByJOINS(false, $columns, [], [], 'id DESC');
    $options = '<option value="" disabled selected>Seleccionar...</option>';
    foreach ($events as $event) {
      $options .= "<option value='" . $event['id'] . "'>" . $event['name'] . "</option>";
    }

    echo $options;
  }
}