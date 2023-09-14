<?php

require_once(__DIR__ . '/../models/event.php');

class EventsController extends Controller
{
  private $spanishMonthNames = [
    'Jan' => 'Ene',
    'Feb' => 'Feb',
    'Mar' => 'Mar',
    'Apr' => 'Abr',
    'May' => 'May',
    'Jun' => 'Jun',
    'Jul' => 'Jul',
    'Aug' => 'Ago',
    'Sep' => 'Sep',
    'Oct' => 'Oct',
    'Nov' => 'Nov',
    'Dec' => 'Dic'
  ];

  private $eventModel;

  public function __construct(PDO $con)
  {
    parent::__construct($con);
    $this->eventModel = new Event($con);
  }

  public function index()
  {
    if ($_SESSION['role_emp'] == 'Administrador') {
      $this->renderView('events');
    }
  }

  public function eventsCRUD()
  {
    if ($_SESSION['role_emp'] == 'Administrador') {
      $data = [
        'id' => $_POST['id'] ?? '',
        'name' => $_POST['name'] ?? '',
        'start_date' => $_POST['start_event'] ?? '',
        'end_date' => $_POST['end_event'] ?? '',
        'ins_start_date' => $_POST['ins_start_event'] ?? '',
        'ins_end_date' => $_POST['ins_end_event'] ?? ''
      ];

      $option = $_POST['option'] ?? '';

      switch ($option) {
        case 1:
          $this->eventModel->insert($data);
          $events = $this->eventModel->getAllDates();
          break;
        case 2:
          $this->eventModel->updateById($data['id'], $data);
          $events = $this->eventModel->getAllDates();
          break;
        case 3:
          $this->eventModel->deleteById($data['id']);
          $events = $this->eventModel->getAllDates();
          break;
        case 4:
          $events = $this->eventModel->getAllDates();
          break;
      }

      foreach ($events as &$event) {
        $event['start_date'] = strtr($event['start_date'], $this->spanishMonthNames);
        $event['end_date'] = strtr($event['end_date'], $this->spanishMonthNames);
        $event['ins_start_date'] = strtr($event['ins_start_date'], $this->spanishMonthNames);
        $event['ins_end_date'] = strtr($event['ins_end_date'], $this->spanishMonthNames);
      }

      echo json_encode($events, JSON_UNESCAPED_UNICODE);
    }
  }

  public function eventsDates()
  {
    $events = $this->eventModel->getEvent();

    if ($events > 0) {
      $events['start_date'] = strtr($events['start_date'], $this->spanishMonthNames);
      $events['end_date'] = strtr($events['end_date'], $this->spanishMonthNames);

      echo json_encode($events, JSON_UNESCAPED_UNICODE);
    } else {
      $events = [
        "id" => 0,
        "name" => "GarzaSports - Ingresar Evento",
        "start_date" => "Sin definir",
        "end_date" => "Sin definir",
        "inst_start_date" => "Sin definir",
        "inst_end_date" => "Sin definir"
      ];
      echo json_encode($events, JSON_UNESCAPED_UNICODE);
    }
  }
}